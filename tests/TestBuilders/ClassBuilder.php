<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Methods\Method;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

class ClassBuilder extends DefinitionBuilder
{
    /** @var Attribute[] */
    private $attributes = [];

    /** @var Method[] */
    private $methods = [];

    /** @var InterfaceDefinition[] */
    private $interfaces = [];

    public function withAPublicAttribute(string $name, string $type = null): ClassBuilder
    {
        $this->attributes[] = Attribute::public($name, TypeDeclaration::from($type));

        return $this;
    }

    public function withAProtectedAttribute(string $name, string $type = null): ClassBuilder
    {
        $this->attributes[] = Attribute::protected($name, TypeDeclaration::from($type));

        return $this;
    }

    public function withAPrivateAttribute(string $name, string $type = null): ClassBuilder
    {
        $this->attributes[] = Attribute::private($name, TypeDeclaration::from($type));

        return $this;
    }

    public function withAProtectedMethod(string $name, Variable ...$parameters): ClassBuilder
    {
        $this->methods[] = Method::protected($name, $parameters);

        return $this;
    }

    public function withAPrivateMethod(string $name, Variable ...$parameters): ClassBuilder
    {
        $this->methods[] = Method::private($name, $parameters);

        return $this;
    }

    public function withAPublicMethod(string $name, Variable ...$parameters): ClassBuilder
    {
        $this->methods[] = Method::public($name, $parameters);

        return $this;
    }

    public function withAMethod(Method $method): ClassBuilder
    {
        $this->methods[] = $method;

        return $this;
    }

    public function implementing(InterfaceDefinition ...$interfaces): ClassBuilder
    {
        $this->interfaces = array_merge($this->interfaces, $interfaces);

        return $this;
    }

    /** @return ClassDefinition */
    public function build()
    {
        return new ClassDefinition(
            $this->name,
            [],
            $this->methods,
            $this->parent,
            $this->attributes,
            $this->interfaces
        );
    }
}
