<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Filters;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;

/**
 * It will exclude private methods or attributes
 */
final class PrivateVisibilityFilter implements VisibilityFilter
{
    /** @param ClassMethod|Property $member */
    public function accept($member): bool
    {
        return ! $member->isPrivate();
    }
}
