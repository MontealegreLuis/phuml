<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhUml\Code\Modifiers\Visibility;

final class VisibilityBuilder
{
    public function build(Property|ClassMethod|ClassConst $member): Visibility
    {
        return match (true) {
            $member->isPublic() => Visibility::PUBLIC,
            $member->isPrivate() => Visibility::PRIVATE,
            default => Visibility::PROTECTED,
        };
    }

    public function fromFlags(int $flags): Visibility
    {
        return match (true) {
            (bool) ($flags & Class_::MODIFIER_PUBLIC) => Visibility::PUBLIC,
            (bool) ($flags & Class_::MODIFIER_PROTECTED) => Visibility::PROTECTED,
            (bool) ($flags & Class_::MODIFIER_PRIVATE) => Visibility::PRIVATE,
            default => throw UnknownVisibilityFlag::withValue($flags)
        };
    }
}
