<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Filters;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;

/**
 * It will exclude private methods or attributes
 */
final class ProtectedVisibilityFilter implements VisibilityFilter
{
    public function accept(Stmt $member): bool
    {
        if ($member instanceof ClassConst
            || $member instanceof ClassMethod
            || $member instanceof Property) {
            return ! $member->isProtected();
        }

        return false;
    }
}
