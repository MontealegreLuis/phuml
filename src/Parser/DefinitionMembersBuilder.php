<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\AbstractMethod;
use PhUml\Code\Attribute;
use PhUml\Code\AttributeDocBlock;
use PhUml\Code\Constant;
use PhUml\Code\Method;
use PhUml\Code\MethodDocBlock;
use PhUml\Code\StaticAttribute;
use PhUml\Code\StaticMethod;
use PhUml\Code\TypeDeclaration;
use PhUml\Code\Variable;
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

    /** @return Attribute[] */
    public function attributes(RawDefinition $class): array
    {
        return array_map(function (array $attribute) {
            [$name, $modifier, $comment, $isStatic] = $attribute;
            if ($isStatic) {
                return StaticAttribute::$modifier($name, $this->extractTypeFrom($comment));
            }
            return Attribute::$modifier($name, $this->extractTypeFrom($comment));
        }, $class->attributes());
    }

    /** @return Constant[] */
    public function constants(RawDefinition $definition): array
    {
        return array_map(function (array $constant) {
            [$name, $type] = $constant;
            return new Constant($name, TypeDeclaration::from($type));
        }, $definition->constants());
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
            [$name, $type] = $parameter;
            return Variable::declaredWith($name, TypeDeclaration::from($type));
        }, $parameters);
    }

    private function extractTypeFrom(?string $comment): TypeDeclaration
    {
        if ($comment === null) {
            return TypeDeclaration::absent();
        }

        return AttributeDocBlock::from($comment)->getType();
    }
}
