<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedAttribute = Attribute::public('$namedAttribute');

        $name = $namedAttribute->name;

        $this->assertEquals('$namedAttribute', $name);
    }

    /** @test */
    function it_can_be_public()
    {
        $publicAttribute = Attribute::public('$attribute');

        $modifier = $publicAttribute->modifier;

        $this->assertEquals(Visibility::public(), $modifier);
    }

    /** @test */
    function it_can_be_protected()
    {
        $publicAttribute = Attribute::protected('$attribute');

        $modifier = $publicAttribute->modifier;

        $this->assertEquals(Visibility::protected(), $modifier);
    }

    /** @test */
    function it_can_be_private()
    {
        $publicAttribute = Attribute::private('$attribute');

        $modifier = $publicAttribute->modifier;

        $this->assertEquals(Visibility::private(), $modifier);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeAttribute = Attribute::public('$attribute');

        $type = $noTypeAttribute->type;

        $this->assertFalse($type->isPresent());
    }

    /** @test */
    function it_knows_it_refers_to_another_class_or_interface()
    {
        $reference = Attribute::public ('reference', TypeDeclaration::from('AClass'));

        $isAReference = $reference->isAReference();

        $this->assertTrue($isAReference);
    }

    /** @test */
    function it_knows_it_does_not_refer_to_another_class_or_interface()
    {
        $noType = Attribute::public('noTypeAttribute');
        $builtInType = Attribute::public('builtInAttribute', TypeDeclaration::from('float'));

        $this->assertFalse($noType->isAReference());
        $this->assertFalse($builtInType->isAReference());
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
}
