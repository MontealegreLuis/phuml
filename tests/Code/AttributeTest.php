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
        $namedAttribute = new Attribute('namedAttribute');

        $name = $namedAttribute->name;

        $this->assertEquals('namedAttribute', $name);
    }


    /** @test */
    function it_is_public_by_default()
    {
        $publicAttribute = new Attribute('attribute');

        $modifier = $publicAttribute->modifier;

        $this->assertEquals('public', $modifier);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeAttribute = new Attribute('attribute');

        $type = $noTypeAttribute->type;

        $this->assertFalse($type->isPresent());
    }

    /** @test */
    function it_knows_if_it_does_not_have_a_type()
    {
        $noTypeAttribute = new Attribute('noTypeAttribute');

        $hasType = $noTypeAttribute->hasType();

        $this->assertFalse($hasType);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $privateAttribute = new Attribute('privateAttribute', 'private');
        $publicAttribute = new Attribute('publicAttribute', 'public');
        $protectedAttribute = new Attribute('protectedAttribute', 'protected');

        $private = $privateAttribute->__toString();
        $public = $publicAttribute->__toString();
        $protected = $protectedAttribute->__toString();

        $this->assertEquals('-privateAttribute', $private);
        $this->assertEquals('+publicAttribute', $public);
        $this->assertEquals('#protectedAttribute', $protected);
    }
}
