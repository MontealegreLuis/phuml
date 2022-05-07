<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Intersection;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;

final class TagTypeFactory
{
    public function __construct(private readonly DocBlockFactory $factory)
    {
    }

    public function typeFromTag(string $comment, TagName $tagName, callable $filter = null): ?TagType
    {
        $docBlock = $this->factory->create($comment);

        /** @var TagWithType[]|InvalidTag[] $tags */
        $tags = $docBlock->getTagsByName($tagName->value);

        // Parameter tags will return multiple values, we use the filter to find the one for a given parameter name
        if ($filter !== null) {
            /** @var TagWithType[]|InvalidTag[] $tags */
            $tags = array_values(array_filter($tags, $filter));
        }

        if (count($tags) < 1) {
            return null;
        }

        [$tagName] = $tags;
        if ($tagName instanceof InvalidTag) {
            return null;
        }

        return $this->resolveType($tagName->getType());
    }

    private function resolveType(?Type $type): ?TagType
    {
        return match (true) {
            $type === null => null,
            $type instanceof Nullable => TagType::nullable((string) $type->getActualType()),
            $type instanceof Compound => TagType::union(array_map(strval(...), $type->getIterator()->getArrayCopy())),
            $type instanceof Intersection => TagType::intersection(array_map(strval(...), $type->getIterator()->getArrayCopy())),
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
