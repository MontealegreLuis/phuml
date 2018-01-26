<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders\Filters;

class PrivateMembersFilter implements MembersFilter
{
    /** @param \PhpParser\Node\Stmt\ClassMethod|\PhpParser\Node\Stmt\Property $member */
    public function accept($member): bool
    {
        return !$member->isPrivate();
    }
}
