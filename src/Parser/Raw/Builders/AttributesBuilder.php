<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Stmt\Property;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;

/**
 * It builds an array with the meta-information of a class attribute
 *
 * The generated array has the following structure
 *
 * - name
 * - visibility
 * - doc block
 *
 * You can run one or more filters, the current available filters will exclude
 *
 * - protected attributes
 * - private attributes
 * - both protected and private if both filters are provided
 *
 * @see PrivateMembersFilter
 * @see ProtectedMembersFilter
 */
class AttributesBuilder
{
    /** @var MembersFilter[] */
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function build(array $classAttributes): array
    {
        $attributes = array_filter($classAttributes, function ($attribute) {
            return $attribute instanceof Property;
        });

        return array_map(function (Property $attribute) {
            return [
                "\${$attribute->props[0]->name}",
                $this->resolveVisibility($attribute),
                $attribute->getDocComment()
            ];
        }, $this->runFilters($attributes));
    }

    /**
     * @param Property[] $classAttributes
     * @return Property[]
     */
    private function runFilters(array $classAttributes): array
    {
        $attributes = $classAttributes;
        foreach ($this->filters as $filter) {
            $attributes = array_filter($attributes, [$filter, 'accept']);
        }
        return $attributes;
    }

    private function resolveVisibility(Property $attribute): string
    {
        switch (true) {
            case $attribute->isPublic():
                return 'public';
            case $attribute->isPrivate():
                return 'private';
            default:
                return 'protected';
        }
    }
}
