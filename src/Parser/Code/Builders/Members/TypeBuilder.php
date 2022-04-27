<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\CompositeType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\TypeResolver;
use RuntimeException;

final class TypeBuilder
{
    public function __construct(private readonly TypeResolver $typeResolver)
    {
    }

    public function fromMethodParameter(
        Identifier|Name|ComplexType|null $type,
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
        Identifier|Name|ComplexType|null $type,
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

    public function fromPropertyType(
        Identifier|Name|ComplexType|null $type,
        ?Doc $docBlock,
        UseStatements $useStatements
    ): TypeDeclaration {
        $propertyComment = $docBlock?->getText();
        if ($type === null) {
            return $this->typeResolver->resolveForProperty($propertyComment, $useStatements);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }

        $typeFromDocBlock = $this->typeResolver->resolveForProperty($propertyComment, $useStatements);

        return $typeFromDocBlock->isPresent() ? $typeFromDocBlock : $typeDeclaration;
    }

    private function fromParsedType(Identifier|Name|ComplexType|null $type): TypeDeclaration
    {
        return match (true) {
            $type instanceof NullableType => TypeDeclaration::fromNullable((string) $type->type),
            $type instanceof Name, $type instanceof Identifier => TypeDeclaration::from((string) $type),
            $type === null => TypeDeclaration::absent(),
            $type instanceof UnionType => TypeDeclaration::fromCompositeType(
                $this->fromCompositeType($type),
                CompositeType::UNION
            ),
            $type instanceof IntersectionType => TypeDeclaration::fromCompositeType(
                $this->fromCompositeType($type),
                CompositeType::INTERSECTION
            ),
            default => throw new RuntimeException(sprintf('%s is not supported', $type::class)),
        };
    }

    /** @return string[] */
    private function fromCompositeType(UnionType|IntersectionType $type): array
    {
        return array_map(
            static fn (Identifier|Name $name): string => (string) $name,
            $type->types
        );
    }
}
