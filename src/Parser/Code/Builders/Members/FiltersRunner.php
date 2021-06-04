<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Parser\Code\Builders\Filters\VisibilityFilter;

/**
 * It can run one or more `VisibilityFilter`s.
 * Filters will exclude:
 *
 * - protected members
 * - private members
 * - both protected and private members if both filters are provided
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
class FiltersRunner
{
    /** @var VisibilityFilter[] */
    protected $filters;

    /** @param VisibilityFilter[] $filters */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @param Property[]|ClassMethod[] $classMembers
     * @return Property[]|ClassMethod[]
     */
    protected function runFilters(array $classMembers): array
    {
        $attributes = $classMembers;
        foreach ($this->filters as $filter) {
            $attributes = array_filter($attributes, static function (Stmt $member) use ($filter) : bool {
                return $filter->accept($member);
            });
        }
        return $attributes;
    }

    /** @param Property|ClassMethod $member */
    protected function resolveVisibility($member): Visibility
    {
        switch (true) {
            case $member->isPublic():
                return Visibility::public();
            case $member->isPrivate():
                return Visibility::private();
            default:
                return Visibility::protected();
        }
    }
}
