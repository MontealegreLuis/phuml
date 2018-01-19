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
class Method
{
    /** @var string */
    private $name;

    /** @var Visibility */
    private $modifier;

    /** @var Variable[] */
    private $params;

    private function __construct(string $name, Visibility $modifier, array $params = [])
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->params = $params;
    }

    public static function public(string $name, array $params = []): Method
    {
        return new Method($name, Visibility::public(), $params);
    }

    public static function protected(string $name, array $params = []): Method
    {
        return new Method($name, Visibility::protected(), $params);
    }

    public static function private(string $name, array $params = []): Method
    {
        return new Method($name, Visibility::private(), $params);
    }

    public function isConstructor(): bool
    {
        return $this->name === '__construct';
    }

    public function name(): string
    {
        return $this->name;
    }

    public function modifier(): Visibility
    {
        return $this->modifier;
    }

    public function params(): array
    {
        return $this->params;
    }

    public function __toString()
    {
        return sprintf(
            '%s%s%s',
            $this->modifier,
            $this->name,
            empty($this->params) ? '()' : '( ' . implode($this->params, ', ') . ' )'
        );
    }
}
