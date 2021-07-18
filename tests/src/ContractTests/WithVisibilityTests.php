<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Fakes\WithVisibilityAssertions;

trait WithVisibilityTests
{
    use WithVisibilityAssertions;

    /** @test */
    function it_can_be_public()
    {
        $publicAttribute = $this->publicMember();

        $this->assertPublic($publicAttribute);
    }

    /** @test */
    function it_can_be_protected()
    {
        $protectedAttribute = $this->protectedMember();

        $this->assertProtected($protectedAttribute);
    }

    /** @test */
    function it_can_be_private()
    {
        $privateAttribute = $this->privateMember();

        $this->assertPrivate($privateAttribute);
    }

    abstract protected function publicMember(): HasVisibility;

    abstract protected function protectedMember(): HasVisibility;

    abstract protected function privateMember(): HasVisibility;
}
