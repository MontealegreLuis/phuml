<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PhUml\Code\HasVisibility;

trait WithVisibilityTests
{
    /** @test */
    function it_can_be_public()
    {
        $publicAttribute = $this->publicMember();

        $isPublic = $publicAttribute->isPublic();

        $this->assertTrue($isPublic);
    }

    /** @test */
    function it_can_be_protected()
    {
        $protectedAttribute = $this->protectedMember();

        $isProtected = $protectedAttribute->isProtected();

        $this->assertTrue($isProtected);
    }

    /** @test */
    function it_can_be_private()
    {
        $privateAttribute = $this->privateMember();

        $isPrivate = $privateAttribute->isPrivate();

        $this->assertTrue($isPrivate);
    }

    abstract protected function publicMember(): HasVisibility;

    abstract protected function protectedMember(): HasVisibility;

    abstract protected function privateMember(): HasVisibility;
}
