<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

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
class MembersBuilder
{
    /** @var VisibilityFilter[] */
    protected $filters;

    /** @param VisibilityFilter */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @param \PhpParser\Node\Stmt\Property[]|\PhpParser\Node\Stmt\ClassMethod $classMembers
     * @return \PhpParser\Node\Stmt\Property[]|\PhpParser\Node\Stmt\ClassMethod
     */
    protected function runFilters(array $classMembers): array
    {
        $attributes = $classMembers;
        foreach ($this->filters as $filter) {
            $attributes = array_filter($attributes, [$filter, 'accept']);
        }
        return $attributes;
    }

    /** @param \PhpParser\Node\Stmt\Property|\PhpParser\Node\Stmt\ClassMethod $member */
    protected function resolveVisibility($member): string
    {
        switch (true) {
            case $member->isPublic():
                return 'public';
            case $member->isPrivate():
                return 'private';
            default:
                return 'protected';
        }
    }
}
