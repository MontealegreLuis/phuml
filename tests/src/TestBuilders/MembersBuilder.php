<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Methods\Method;
use PhUml\Code\Variables\Variable;

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
        $this->attributes[] = Attribute::protected(A::variable($name)->withType($type)->build());

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPrivateAttribute(string $name, string $type = null)
    {
        $this->attributes[] = Attribute::private(A::variable($name)->withType($type)->build());

        return $this;
    }

    public function withAnAttribute(Attribute $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAProtectedMethod(string $name, Variable ...$parameters)
    {
        $this->methods[] = Method::protected($name, $parameters);

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPrivateMethod(string $name, Variable ...$parameters)
    {
        $this->methods[] = Method::private($name, $parameters);

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAPublicMethod(string $name, Variable ...$parameters)
    {
        $this->methods[] = Method::public($name, $parameters);

        return $this;
    }

    /** @return ClassBuilder|TraitBuilder */
    public function withAMethod(Method $method)
    {
        $this->methods[] = $method;

        return $this;
    }
}
