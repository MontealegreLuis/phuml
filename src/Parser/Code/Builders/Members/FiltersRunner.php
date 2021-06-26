<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt;
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
            $attributes = array_filter($attributes, static function (Stmt $member) use ($filter): bool {
                return $filter->accept($member);
            });
        }
        return $attributes;
    }
}
