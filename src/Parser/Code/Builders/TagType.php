<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhUml\Code\Name;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\CompositeType;
use PhUml\Code\Variables\TypeDeclaration;

final class TagType
{
    public static function nullable(string $type): TagType
    {
        return new TagType([$type], isNullable: true);
    }

    /** @param string[] $types */
    public static function union(array $types): TagType
    {
        return new TagType($types, isNullable: false, compositeType: CompositeType::UNION);
    }

    /** @param string[] $types */
    public static function intersection(array $types): TagType
    {
        return new TagType($types, isNullable: false, compositeType: CompositeType::INTERSECTION);
    }

    public static function named(string $type): TagType
    {
        return new TagType([$type]);
    }

    /** @param string[] $types */
    private function __construct(
        private readonly array $types,
        private readonly bool $isNullable = false,
        private readonly CompositeType $compositeType = CompositeType::NONE
    ) {
    }

    public function resolve(UseStatements $useStatements): TypeDeclaration
    {
        return match (true) {
            $this->isNullable => TypeDeclaration::fromNullable($useStatements->fullyQualifiedNameFor($this->types()[0])),
            count($this->types) === 1 => TypeDeclaration::from($useStatements->fullyQualifiedNameFor($this->types()[0])),
            default => $this->resolveCompositeTypes($useStatements),
        };
    }

    private function resolveCompositeTypes(UseStatements $useStatements): TypeDeclaration
    {
        $withFullyQualifiedNames = array_map(
            static fn (Name $type) => $useStatements->fullyQualifiedNameFor($type),
            $this->types(),
        );
        return TypeDeclaration::fromCompositeType($withFullyQualifiedNames, $this->compositeType);
    }

    /** @return Name[] */
    private function types(): array
    {
        return array_map(static fn (string $type) => new Name(ltrim($type, characters: '\\')), $this->types);
    }
}
