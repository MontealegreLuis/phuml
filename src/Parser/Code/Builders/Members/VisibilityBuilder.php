<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhUml\Code\Modifiers\Visibility;

final class VisibilityBuilder
{
    /** @param Property|ClassMethod|ClassConst $member */
    public function build($member): Visibility
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
