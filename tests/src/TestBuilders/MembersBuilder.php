<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Parameters\Parameter;

trait MembersBuilder
{
    /** @var Attribute[] */
    protected $attributes = [];

    /** @var Method[] */
    protected $methods = [];

    /** @return ClassBuilder|TraitBuilder */
    public function withAPublicAttribute(string $name, string $type = null)
    {
        $this->attributes[] = A::attribute($name)->public()->withType($type)->build();

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAProtectedAttribute(string $name, string $type = null)
    {
        $this->attributes[] = new Attribute(A::variable($name)->withType($type)->build(), Visibility::protected());

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPrivateAttribute(string $name, string $type = null)
    {
        $this->attributes[] = new Attribute(A::variable($name)->withType($type)->build(), Visibility::private());

        return $this;
    }

    public function withAnAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAProtectedMethod(string $name, Parameter ...$parameters)
    {
        $this->methods[] = A::method($name)->protected()->withParameters(...$parameters)->build();

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPrivateMethod(string $name, Parameter ...$parameters)
    {
        $this->methods[] = A::method($name)->private()->withParameters(...$parameters)->build();

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPublicMethod(string $name, Parameter ...$parameters)
    {
        $this->methods[] = A::method($name)->public()->withParameters(...$parameters)->build();

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAMethod(Method $method)
    {
        $this->methods[] = $method;

        return $this;
    }
}
