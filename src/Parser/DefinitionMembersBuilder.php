<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\Methods\AbstractMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Methods\StaticMethod;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
use PhUml\Parser\Raw\RawDefinition;

/**
 * It builds the attributes and methods of both classes and interfaces
 */
class DefinitionMembersBuilder
{
    /** @return Method[] */
    public function methods(RawDefinition $definition): array
    {
        return array_map(function (array $method) {
            return $this->buildMethod($method);
        }, $definition->methods());
    }

    /** @return \PhUml\Code\Attributes\Attribute[] */
    public function attributes(RawDefinition $class): array
    {
        return $class->attributes();
    }

    /** @return \PhUml\Code\Attributes\Constant[] */
    public function constants(RawDefinition $definition): array
    {
        return $definition->constants();
    }

    private function buildMethod(array $method): Method
    {
        [$name, $modifier, $parameters, $isAbstract, $isStatic, $comment] = $method;
        $returnType = MethodDocBlock::from($comment)->returnType();
        if ($isAbstract) {
            return AbstractMethod::$modifier($name, $this->buildParameters($parameters), $returnType);
        }
        if ($isStatic) {
            return StaticMethod::$modifier($name, $this->buildParameters($parameters), $returnType);
        }
        return Method::$modifier($name, $this->buildParameters($parameters), $returnType);
    }

    /** @return Variable[] */
    private function buildParameters(array $parameters): array
    {
        return array_map(function (array $parameter) {
            [$name, $type, $comment] = $parameter;
            if ($type !== null) {
                $typeDeclaration = TypeDeclaration::from($type);
            } else {
                $typeDeclaration = MethodDocBlock::from($comment)->typeOfParameter($name);
            }
            return Variable::declaredWith($name, $typeDeclaration);
        }, $parameters);
    }
}
