<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\ContractTests\WithVisibilityTests;
use PhUml\ContractTests\WithTypeDeclarationTests;

class AttributeTest extends TestCase
{
    use WithTypeDeclarationTests, WithVisibilityTests;

    /** @test */
    function it_knows_its_name()
    {
        $namedAttribute = Attribute::public('$namedAttribute');

        $name = $namedAttribute->name();

        $this->assertEquals('$namedAttribute', $name);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $privateAttribute = Attribute::private('privateAttribute');
        $publicAttribute = Attribute::public('publicAttribute');
        $protectedAttribute = Attribute::protected('protectedAttribute');

        $private = $privateAttribute->__toString();
        $public = $publicAttribute->__toString();
        $protected = $protectedAttribute->__toString();

        $this->assertEquals('-privateAttribute', $private);
        $this->assertEquals('+publicAttribute', $public);
        $this->assertEquals('#protectedAttribute', $protected);
    }

    protected function memberWithoutType(): HasType
    {
        return Attribute::public('$attribute');
    }

    protected function reference(): HasType
    {
        return Attribute::public('reference', TypeDeclaration::from('AClass'));
    }

    protected function memberWithBuiltInType(): HasType
    {
        return Attribute::public('builtInAttribute', TypeDeclaration::from('float'));
    }

    protected function publicMember(): HasVisibility
    {
        return Attribute::public('$attribute');
    }

    protected function protectedMember(): HasVisibility
    {
        return Attribute::protected('$attribute');
    }

    protected function privateMember(): HasVisibility
    {
        return Attribute::private('$attribute');
    }
}
