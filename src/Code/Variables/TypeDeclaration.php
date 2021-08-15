<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PhUml\Code\Name;
use Stringable;

/**
 * It represents a variable's type declaration
 */
final class TypeDeclaration implements Stringable
{
    /** @var string[] All valid types for PHP 8.0, pseudo-types, and aliases */
    private const BUILT_IN_TYPES = [
        // https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.base
        'int', 'bool', 'string', 'array', 'float', 'callable', 'iterable', 'mixed', 'object',
        // https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.union.nullable
        'null',
        // https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.return-only
        'void',
        // pseudo-types
        'resource',
        // aliases
        'number', 'boolean', 'integer', 'double',
    ];

    /** @var Name[] */
    private array $names;

    public static function absent(): TypeDeclaration
    {
        return new TypeDeclaration();
    }

    public static function from(?string $type): TypeDeclaration
    {
        return new TypeDeclaration($type === null ? [] : [new Name($type)]);
    }

    public static function fromNullable(string $type): TypeDeclaration
    {
        return new TypeDeclaration([new Name($type)], isNullable: true);
    }

    /** @param string[] $types */
    public static function fromUnionType(array $types): TypeDeclaration
    {
        return new TypeDeclaration(array_map(static fn (string $type) => new Name($type), $types));
    }

    public function isPresent(): bool
    {
        return $this->names !== [];
    }

    /** @return Name[] */
    public function references(): array
    {
        if (! $this->isPresent()) {
            return [];
        }
        if ($this->isBuiltIn()) {
            return [];
        }
        if ($this->isRegularType()) {
            return [$this->isArray() ? new Name($this->removeArraySuffix()) : $this->names[0]];
        }

        $typesFromUnion = array_map(static fn (Name $name) => TypeDeclaration::from((string) $name), $this->names);
        $references = array_filter($typesFromUnion, static fn (TypeDeclaration $type) => ! $type->isBuiltIn());

        return array_map(
            static fn (TypeDeclaration $reference) => $reference->isArray()
                ? new Name($reference->removeArraySuffix())
                : $reference->names[0],
            $references
        );
    }

    /**
     * It helps building the relationships between classes/interfaces since built-in
     * types are not part of a UML class diagram
     */
    public function isBuiltIn(): bool
    {
        if (! $this->isRegularType()) {
            return false;
        }
        $type = (string) $this->names[0];
        if ($this->isArray()) {
            $type = $this->removeArraySuffix();
        }

        return in_array($type, self::BUILT_IN_TYPES, true);
    }

    private function removeArraySuffix(): string
    {
        return substr($this->names[0]->fullName(), 0, -2);
    }

    public function isBuiltInArray(): bool
    {
        if ($this->isRegularType()) {
            return (string) $this->names[0] === 'array';
        }
        return false;
    }

    private function isArray(): bool
    {
        return str_ends_with($this->names[0]->fullName(), '[]');
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    private function isRegularType(): bool
    {
        return count($this->names) === 1;
    }

    public function __toString(): string
    {
        return ($this->isNullable ? '?' : '') . implode('|', $this->names);
    }

    /** @param Name[] $names */
    private function __construct(array $names = [], private bool $isNullable = false)
    {
        $this->names = $names;
    }
}
