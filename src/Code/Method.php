<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * It represents a class or interface method
 *
 * It doesn't distinguish neither static methods nor return types yet
 */
class Method implements HasVisibility, CanBeAbstract
{
    use WithVisibility, WithAbstractModifier;

    /** @var string */
    private $name;

    /** @var Variable[] */
    private $parameters;

    protected function __construct(string $name, Visibility $modifier, array $parameters = [])
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->parameters = $parameters;
        $this->isAbstract = false;
    }

    /** @param Variable[] $parameters */
    public static function public(string $name, array $parameters = []): Method
    {
        return new static($name, Visibility::public (), $parameters);
    }

    /** @param Variable[] $parameters */
    public static function protected(string $name, array $parameters = []): Method
    {
        return new static($name, Visibility::protected (), $parameters);
    }

    /** @param Variable[] $parameters */
    public static function private(string $name, array $parameters = []): Method
    {
        return new static($name, Visibility::private (), $parameters);
    }

    public function isConstructor(): bool
    {
        return $this->name === '__construct';
    }

    public function name(): string
    {
        return $this->name;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function __toString()
    {
        return sprintf(
            '%s%s%s',
            $this->modifier,
            $this->name,
            empty($this->parameters) ? '()' : '( ' . implode($this->parameters, ', ') . ' )'
        );
    }
}
