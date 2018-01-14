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
    private static $symbols = [
        'private' => '-',
        'protected' => '#',
        'public' => '+',
    ];

    /** @var string */
    public $name;

    /** @var string */
    public $modifier;

    /** @var Variable[] */
    public $params;

    private function __construct(string $name, string $modifier = 'public', array $params = [])
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->params = $params;
    }

    public static function public(string $name, array $params = []): Method
    {
        return new Method($name, 'public', $params);
    }

    public static function protected(string $name, array $params = []): Method
    {
        return new Method($name, 'protected', $params);
    }

    public static function private(string $name, array $params = []): Method
    {
        return new Method($name, 'private', $params);
    }

    public function isConstructor(): bool
    {
        return $this->name === '__construct';
    }

    public function __toString()
    {
        return sprintf(
            '%s%s%s',
            self::$symbols[$this->modifier],
            $this->name,
            empty($this->params) ? '()' : '( ' . implode($this->params, ', ') . ' )'
        );
    }
}
