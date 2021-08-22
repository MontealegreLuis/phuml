<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhUml\Code\Name;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;

final class TagType
{
    public static function nullable(string $type): TagType
    {
        return new TagType([$type], isNullable: true);
    }

    /** @param string[] $types */
    public static function compound(array $types): TagType
    {
        return new TagType($types, isNullable: false);
    }

    public static function named(string $type): TagType
    {
        return new TagType([$type]);
    }

    /** @param string[] $types */
    private function __construct(private array $types, private bool $isNullable = false)
    {
    }

    public function resolve(UseStatements $useStatements): TypeDeclaration
    {
        return match (true) {
            $this->isNullable => TypeDeclaration::fromNullable($useStatements->fullyQualifiedNameFor($this->types()[0])),
            count($this->types) === 1 => TypeDeclaration::from($useStatements->fullyQualifiedNameFor($this->types()[0])),
            default => $this->resolveUnionTypes($useStatements),
        };
    }

    private function resolveUnionTypes(UseStatements $useStatements): TypeDeclaration
    {
        $withFullyQualifiedNames = array_map(
            static fn (Name $type) => $useStatements->fullyQualifiedNameFor($type),
            $this->types(),
        );
        return TypeDeclaration::fromUnionType($withFullyQualifiedNames);
    }

    /** @return Name[] */
    private function types(): array
    {
        return array_map(static fn (string $type) => new Name(ltrim($type, characters: '\\')), $this->types);
    }
}
