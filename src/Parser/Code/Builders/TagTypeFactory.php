<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;

final class TagTypeFactory
{
    public function __construct(private DocBlockFactory $factory)
    {
    }

    public function parameterTypeFrom(string $methodComment, string $parameterName): ?TagType
    {
        $docBlock = $this->factory->create($methodComment);

        /** @var TagType[]|InvalidTag[] $parameterTags */
        $parameterTags = $docBlock->getTagsByName('param');

        /** @var Param[] $params */
        $params = array_values(array_filter(
            $parameterTags,
            static fn (TagWithType|InvalidTag $parameter) =>
               $parameter instanceof Param && "\${$parameter->getVariableName()}" === $parameterName
        ));

        if (count($params) < 1) {
            return null;
        }

        [$param] = $params;

        return $this->resolveType($param->getType());
    }

    public function returnTypeFrom(string $methodComment): ?TagType
    {
        $docBlock = $this->factory->create($methodComment);

        /** @var TagWithType[]|InvalidTag[] $returnTags */
        $returnTags = $docBlock->getTagsByName('return');

        if (count($returnTags) < 1) {
            return null;
        }

        [$return] = $returnTags;
        if ($return instanceof InvalidTag) {
            return null;
        }

        return $this->resolveType($return->getType());
    }

    public function attributeTypeFrom(string $attributeComment): ?TagType
    {
        $docBlock = $this->factory->create($attributeComment);

        /** @var TagWithType[]|InvalidTag[] $varTags */
        $varTags = $docBlock->getTagsByName('var');

        if (count($varTags) < 1) {
            return null;
        }

        [$var] = $varTags;
        if ($var instanceof InvalidTag) {
            return null;
        }

        return $this->resolveType($var->getType());
    }

    private function resolveType(?Type $type): ?TagType
    {
        return match (true) {
            $type === null => null,
            $type instanceof Nullable => TagType::nullable((string) $type->getActualType()),
            $type instanceof Compound => TagType::compound(array_map('strval', $type->getIterator()->getArrayCopy())),
            default => $this->fromType($type)
        };
    }

    private function fromType(Type $type): TagType
    {
        if (! $type instanceof Object_) {
            return TagType::named((string) $type);
        }
        if ($type->getFqsen() === null) {
            return TagType::named((string) $type);
        }
        return TagType::named((string) $type->getFqsen());
    }
}
