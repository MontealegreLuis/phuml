<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Filters;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;

/**
 * It will exclude private methods or attributes
 */
final class PrivateVisibilityFilter implements VisibilityFilter
{
    public function accept(Stmt|Param $member): bool
    {
        if ($member instanceof ClassConst
            || $member instanceof ClassMethod
            || $member instanceof Property) {
            return ! $member->isPrivate();
        }

        if ($member instanceof Param) {
            return ! (bool) ($member->flags & Class_::MODIFIER_PRIVATE);
        }

        return false;
    }
}
