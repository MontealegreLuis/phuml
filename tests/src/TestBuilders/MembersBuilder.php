<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Parameters\Parameter;
use PhUml\Code\Properties\Property;

trait MembersBuilder
{
    /** @var Property[] */
    protected array $properties = [];

    /** @var Method[] */
    protected array $methods = [];

    /** @return ClassBuilder|TraitBuilder */
    public function withAPublicProperty(string $name, string $type = null)
    {
        $this->properties[] = A::property($name)->public()->withType($type)->build();

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAProtectedProperty(string $name, string $type = null)
    {
        $this->properties[] = new Property(A::variable($name)->withType($type)->build(), Visibility::protected());

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPrivateProperty(string $name, string $type = null)
    {
        $this->properties[] = new Property(A::variable($name)->withType($type)->build(), Visibility::private());

        return $this;
    }

    public function withAProperty(Property $property)
    {
        $this->properties[] = $property;

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
