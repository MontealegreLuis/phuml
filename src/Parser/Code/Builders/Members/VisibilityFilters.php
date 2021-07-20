<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
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
final class VisibilityFilters
{
    /** @var VisibilityFilter[] */
    private array $filters;

    /** @param VisibilityFilter[] $filters */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @param Property[]|ClassMethod[]|ClassConst[] $classMembers
     * @return Property[]|ClassMethod[]|ClassConst[]
     */
    public function apply(array $classMembers): array
    {
        $attributes = $classMembers;
        foreach ($this->filters as $filter) {
            $attributes = array_filter($attributes, static fn (Stmt $member): bool => $filter->accept($member));
        }
        return $attributes;
    }
}
