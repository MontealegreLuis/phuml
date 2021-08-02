<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Nullable;
use PhUml\Code\Name;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;

final class TypeResolver
{
    public function __construct(private DocBlockFactory $factory)
    {
    }

    public function resolveForParameter(?string $methodComment, string $name, UseStatements $useStatements): TypeDeclaration
    {
        if ($methodComment === null) {
            return TypeDeclaration::absent();
        }

        $docBlock = $this->factory->create($methodComment);

        /** @var TagWithType[]|InvalidTag[] $parameterTags */
        $parameterTags = $docBlock->getTagsByName('param');

        /** @var Param[] $params */
        $params = array_values(array_filter(
            $parameterTags,
            static fn (TagWithType|InvalidTag $parameter) =>
                $parameter instanceof Param && "\${$parameter->getVariableName()}" === $name
        ));
        if (count($params) < 1) {
            return TypeDeclaration::absent();
        }

        [$param] = $params;

        return $this->declarationFromTagType($param->getType(), $useStatements);
    }

    public function resolveForReturn(?string $methodComment, UseStatements $useStatements): TypeDeclaration
    {
        if ($methodComment === null) {
            return TypeDeclaration::absent();
        }

        $docBlock = $this->factory->create($methodComment);

        /** @var TagWithType[]|InvalidTag[] $returnTags */
        $returnTags = $docBlock->getTagsByName('return');

        if (count($returnTags) < 1) {
            return TypeDeclaration::absent();
        }

        [$return] = $returnTags;
        if ($return instanceof InvalidTag) {
            return TypeDeclaration::absent();
        }

        return $this->declarationFromTagType($return->getType(), $useStatements);
    }

    public function resolveForAttribute(?string $attributeComment, UseStatements $useStatements): TypeDeclaration
    {
        if ($attributeComment === null) {
            return TypeDeclaration::absent();
        }

        $docBlock = $this->factory->create($attributeComment);

        /** @var TagWithType[]|InvalidTag[] $varTags */
        $varTags = $docBlock->getTagsByName('var');

        if (count($varTags) < 1) {
            return TypeDeclaration::absent();
        }

        [$var] = $varTags;
        if ($var instanceof InvalidTag) {
            return TypeDeclaration::absent();
        }

        return $this->declarationFromTagType($var->getType(), $useStatements);
    }

    private function declarationFromTagType(?Type $tagType, UseStatements $useStatements): TypeDeclaration
    {
        return match (true) {
            $tagType === null => TypeDeclaration::absent(),
            $tagType instanceof Nullable => $this->resolveNullableType($tagType, $useStatements),
            $tagType instanceof Compound => $this->resolveUnionTypes($tagType, $useStatements),
            default => $this->resolveType($tagType, $useStatements)
        };
    }

    private function resolveUnionTypes(Compound $tagType, UseStatements $useStatements): TypeDeclaration
    {
        $withFullyQualifiedNames = array_map(
            fn (string $type) => $useStatements->fullyQualifiedNameFor(new Name($this->unqualify($type))),
            $tagType->getIterator()->getArrayCopy()
        );
        return TypeDeclaration::fromUnionType($withFullyQualifiedNames);
    }

    private function resolveType(Type $tagType, UseStatements $useStatements): TypeDeclaration
    {
        $type = $this->unqualify((string) $tagType);
        $fullyQualifiedName = $useStatements->fullyQualifiedNameFor(new Name($type));
        return TypeDeclaration::from($fullyQualifiedName);
    }

    private function resolveNullableType(Nullable $nullable, UseStatements $useStatements): TypeDeclaration
    {
        $type = $this->unqualify((string) $nullable->getActualType());
        $fullyQualifiedName = $useStatements->fullyQualifiedNameFor(new Name($type));
        return TypeDeclaration::fromNullable($fullyQualifiedName);
    }

    private function unqualify(string $name): string
    {
        return ltrim($name, characters: '\\');
    }
}
