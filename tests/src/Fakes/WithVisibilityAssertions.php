<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\Modifiers\HasVisibility;

trait WithVisibilityAssertions
{
    public function assertPublic(HasVisibility $member): void
    {
        $this->assertTrue($member->isPublic(), 'Member is not public');
    }

    public function assertProtected(HasVisibility $member): void
    {
        $this->assertTrue($member->isProtected(), 'Member is not protected');
    }

    public function assertPrivate(HasVisibility $member): void
    {
        $this->assertTrue($member->isPrivate(), 'Member is not private');
    }
}
