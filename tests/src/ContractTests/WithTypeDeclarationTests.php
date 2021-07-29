<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PhUml\Code\Variables\HasType;

trait WithTypeDeclarationTests
{
    /** @test */
    function it_has_no_type_by_default()
    {
        $memberWithoutType = $this->memberWithoutType();

        $hasTypeDeclaration = $memberWithoutType->hasTypeDeclaration();

        $this->assertFalse($hasTypeDeclaration);
    }

    /** @test */
    function it_knows_if_it_refers_to_another_class_or_interface()
    {
        $reference = $this->typeDeclaration();

        $references = $reference->references();

        $this->assertCount(1, $references);
    }

    /** @test */
    function it_knows_it_does_not_refers_to_another_class_or_interface()
    {
        $noType = $this->memberWithoutType();
        $builtInType = $this->memberWithBuiltInType();

        $this->assertCount(0, $noType->references());
        $this->assertCount(0, $builtInType->references());
    }

    abstract protected function memberWithoutType(): HasType;

    abstract protected function typeDeclaration(): HasType;

    abstract protected function memberWithBuiltInType(): HasType;
}
