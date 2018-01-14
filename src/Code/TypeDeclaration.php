<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class TypeDeclaration
{
    /** @var string[] All valid types for PHP 7.1 */
    private static $builtInTypes = [
        'int', 'bool', 'string', 'array', 'float', 'callable', 'iterable'
    ];

    /** @var string */
    private $name;

    public function __construct(?string $name)
    {
        $this->name = $name;
    }

    public static function absent(): TypeDeclaration
    {
        return new TypeDeclaration(null);
    }

    public static function from(?string $text): TypeDeclaration
    {
        return new TypeDeclaration($text);
    }

    public function isPresent(): bool
    {
        return !empty($this->name); // $name cannot be null because of the type hint in the constructor
    }

    /**
     * This will help when building the relationships between classes/interfaces since built-in
     * types are not part of a UML class diagram
     */
    public function isBuiltIn(): bool
    {
        return null !== $this->name && \in_array($this->name, self::$builtInTypes, true);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
