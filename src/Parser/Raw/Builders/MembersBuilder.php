<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhUml\Parser\Raw\Builders\Filters\MembersFilter;

class MembersBuilder
{
    /** @var MembersFilter[] */
    protected $filters;

    /** @param MembersFilter */
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
