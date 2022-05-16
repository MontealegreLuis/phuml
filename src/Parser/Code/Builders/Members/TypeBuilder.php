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
use PhUml\Parser\Code\Builders\TagName;
use PhUml\Parser\Code\TypeResolver;
use RuntimeException;

final class TypeBuilder
{
    public function __construct(private readonly TypeResolver $typeResolver)
    {
    }

    public function fromType(
        Identifier|Name|ComplexType|null $type,
        ?Doc $docBlock,
        TagName $tagName,
        UseStatements $useStatements,
        callable $filter = null,
    ): TypeDeclaration {
        $comment = $docBlock?->getText();
        if ($type === null) {
            return $this->typeResolver->resolveFromDocBlock($comment, $tagName, $useStatements, $filter);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }

        $typeFromDocBlock = $this->typeResolver->resolveFromDocBlock($comment, $tagName, $useStatements, $filter);

        return $typeFromDocBlock->isPresent() ? $typeFromDocBlock : $typeDeclaration;
    }

    private function fromParsedType(Identifier|Name|ComplexType|null $type): TypeDeclaration
    {
        return match (true) {
            $type instanceof NullableType => TypeDeclaration::fromNullable((string) $type->type),
            $type instanceof Name, $type instanceof Identifier => TypeDeclaration::from((string) $type),
            $type === null => TypeDeclaration::absent(),
            $type instanceof UnionType => TypeDeclaration::fromCompositeType(
                array_map(strval(...), $type->types),
                CompositeType::UNION
            ),
            $type instanceof IntersectionType => TypeDeclaration::fromCompositeType(
                array_map(strval(...), $type->types),
                CompositeType::INTERSECTION
            ),
            default => throw new RuntimeException(sprintf('%s is not supported', $type::class)),
        };
    }
}
