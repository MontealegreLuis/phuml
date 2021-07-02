<?php declare(strict_types=1);
/**
 * PHP version 7.2
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
final class TypeDeclaration implements Named
{
    use WithName;

    /** @var string[] All valid types for PHP 7.1, pseudo-types, and aliases */
    private static array $builtInTypes = [
        'int', 'bool', 'string', 'array', 'float', 'callable', 'iterable',
        // pseudo-types
        'mixed', 'number', 'object', 'resource', 'self',
        // aliases
        'boolean', 'integer', 'double',
    ];

    private bool $isNullable;

    public static function absent(): TypeDeclaration
    {
        return new TypeDeclaration(null);
    }

    public static function from(?string $type): TypeDeclaration
    {
        return new TypeDeclaration($type);
    }

    public static function fromNullable(string $type): TypeDeclaration
    {
        return new TypeDeclaration($type, true);
    }

    public function isPresent(): bool
    {
        return $this->name !== null;
    }

    /**
     * It helps building the relationships between classes/interfaces since built-in
     * types are not part of a UML class diagram
     *
     * @see \PhUml\Code\Variables\WithTypeDeclaration::isAReference() for more details
     */
    public function isBuiltIn(): bool
    {
        $type = (string) $this->name;
        if ($this->isArray()) {
            $type = $this->removeArraySuffix();
        }
        return $this->isPresent() && \in_array($type, self::$builtInTypes, true);
    }

    public function isArray(): bool
    {
        return strpos((string) $this->name, '[]') === \strlen((string) $this->name) - 2;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function removeArraySuffix(): string
    {
        return substr((string) $this->name, 0, -2);
    }

    public function __toString()
    {
        return ($this->isNullable ? '?' : '') . $this->name;
    }

    private function __construct(?string $name, bool $isNullable = false)
    {
        $this->name = $name !== null ? new Name($name) : null;
        $this->isNullable = $name !== null && $isNullable;
    }
}
