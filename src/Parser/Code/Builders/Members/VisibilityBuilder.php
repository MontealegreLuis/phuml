<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
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
            $member->isPublic() => Visibility::public(),
            $member->isPrivate() => Visibility::private(),
            default => Visibility::protected(),
        };
    }

    public function fromFlags(int $flags): Visibility
    {
        return match (true) {
            (bool) ($flags & Class_::MODIFIER_PUBLIC) => Visibility::public(),
            (bool) ($flags & Class_::MODIFIER_PROTECTED) => Visibility::protected(),
            (bool) ($flags & Class_::MODIFIER_PRIVATE) => Visibility::private(),
            default => throw UnknownVisibilityFlag::withValue($flags)
        };
    }
}
