<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\TypeResolver;

final class TypeBuilder
{
    public function __construct(private TypeResolver $typeResolver)
    {
    }

    public function fromMethodParameter(
        Identifier|Name|NullableType|UnionType|null $type,
        ?Doc $docBlock,
        string $name,
        UseStatements $useStatements
    ): TypeDeclaration {
        $methodComment = $docBlock?->getText();
        if ($type === null) {
            return $this->typeResolver->resolveForParameter($methodComment, $name, $useStatements);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }

        $typeFromDocBlock = $this->typeResolver->resolveForParameter($methodComment, $name, $useStatements);

        return $typeFromDocBlock->isPresent() ? $typeFromDocBlock : $typeDeclaration;
    }

    public function fromMethodReturnType(
        Identifier|Name|NullableType|UnionType|null $type,
        ?Doc $docBlock,
        UseStatements $useStatements
    ): TypeDeclaration {
        $methodComment = $docBlock?->getText();
        if ($type === null) {
            return $this->typeResolver->resolveForReturn($methodComment, $useStatements);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }

        $typeFromDocBlock = $this->typeResolver->resolveForReturn($methodComment, $useStatements);

        return $typeFromDocBlock->isPresent() ? $typeFromDocBlock : $typeDeclaration;
    }

    public function fromAttributeType(
        Identifier|Name|NullableType|UnionType|null $type,
        ?Doc $docBlock,
        UseStatements $useStatements
    ): TypeDeclaration {
        $attributeComment = $docBlock?->getText();
        if ($type === null) {
            return $this->typeResolver->resolveForAttribute($attributeComment, $useStatements);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }

        $typeFromDocBlock = $this->typeResolver->resolveForAttribute($attributeComment, $useStatements);

        return $typeFromDocBlock->isPresent() ? $typeFromDocBlock : $typeDeclaration;
    }

    private function fromParsedType(Identifier|Name|NullableType|UnionType|null $type): TypeDeclaration
    {
        return match (true) {
            $type instanceof NullableType => TypeDeclaration::fromNullable((string) $type->type),
            $type instanceof Name, $type instanceof Identifier => TypeDeclaration::from((string) $type),
            $type === null => TypeDeclaration::absent(),
            default => TypeDeclaration::fromUnionType($this->fromUnionType($type)),
        };
    }

    /** @return string[] */
    private function fromUnionType(UnionType $type): array
    {
        return array_map(
            static fn (Identifier|Name $name): string => (string) $name,
            $type->types
        );
    }
}
