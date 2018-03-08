<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PhUml\Code\Name;
use PhUml\Code\Named;
use PhUml\Code\WithName;

/**
 * It represents a variable's type declaration
 */
class TypeDeclaration implements Named
{
    use WithName;

    /** @var string[] All valid types for PHP 7.1, pseudo-types, and aliases */
    private static $builtInTypes = [
        'int', 'bool', 'string', 'array', 'float', 'callable', 'iterable',
        // pseudo-types
        'mixed', 'number', 'object', 'resource', 'self',
        // aliases
        'boolean', 'integer', 'double'
    ];

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
        return null !== $this->name;
    }

    /**
     * It helps building the relationships between classes/interfaces since built-in
     * types are not part of a UML class diagram
     *
     * @see \PhUml\Code\Variables\WithTypeDeclaration::isAReference() for more details
     */
    public function isBuiltIn(): bool
    {
        $type = (string)$this->name;
        if ($this->isArray()) {
            $type = $this->removeArraySuffix();
        }
        return $this->isPresent() && \in_array($type, self::$builtInTypes, true);
    }

    public function isArray(): bool
    {
        return strpos($this->name, '[]') === \strlen($this->name) - 2;
    }

    public function removeArraySuffix(): string
    {
        return substr($this->name, 0, -2);
    }

    private function __construct(?string $name)
    {
        $this->name = $name !== null ? Name::from($name) : null;
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
