<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plPhpAttributeTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedAttribute = new plPhpAttribute('namedAttribute');

        $name = $namedAttribute->name;

        $this->assertEquals('namedAttribute', $name);
    }


    /** @test */
    function it_is_public_by_default()
    {
        $publicAttribute = new plPhpAttribute('attribute');

        $modifier = $publicAttribute->modifier;

        $this->assertEquals('public', $modifier);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeAttribute = new plPhpAttribute('attribute');

        $type = $noTypeAttribute->type;

        $this->assertNull($type);
    }

    /** @test */
    function it_knows_if_it_does_not_have_a_type()
    {
        $noTypeAttribute = new plPhpAttribute('noTypeAttribute');

        $hasType = $noTypeAttribute->hasType();

        $this->assertFalse($hasType);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $privateAttribute = new plPhpAttribute('privateAttribute', 'private');
        $publicAttribute = new plPhpAttribute('publicAttribute', 'public');
        $protectedAttribute = new plPhpAttribute('protectedAttribute', 'protected');

        $private = $privateAttribute->__toString();
        $public = $publicAttribute->__toString();
        $protected = $protectedAttribute->__toString();

        $this->assertEquals('-privateAttribute', $private);
        $this->assertEquals('+publicAttribute', $public);
        $this->assertEquals('#protectedAttribute', $protected);
    }
}
