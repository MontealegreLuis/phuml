<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\AbstractMethod;
use PhUml\Code\Attribute;
use PhUml\Code\Constant;
use PhUml\Code\Method;
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
            [$name] = $constant;
            return new Constant($name);
        }, $definition->constants());
    }

    private function buildMethod(array $method): Method
    {
        [$name, $modifier, $parameters, $isAbstract, $isStatic] = $method;
        if ($isAbstract) {
            return AbstractMethod::$modifier($name, $this->buildParameters($parameters));
        }
        if ($isStatic) {
            return StaticMethod::$modifier($name, $this->buildParameters($parameters));
        }
        return Method::$modifier($name, $this->buildParameters($parameters));
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

        $type = null;  // There might be no type information in the comment
        $matches = [];
        $arrayExpression = '/^[\s*]*@var\s+array\(\s*(\w+\s*=>\s*)?(\w+)\s*\).*$/m';
        if (preg_match($arrayExpression, $comment, $matches)) {
            $type = $matches[2];
        } else {
            $typeExpression = '/^[\s*]*@var\s+(\S+).*$/m';
            if (preg_match($typeExpression, $comment, $matches)) {
                $type = trim($matches[1]);
            }
        }
        return TypeDeclaration::from($type);
    }
}
