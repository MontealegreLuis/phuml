<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    /** @test */
    function it_extract_its_namespace()
    {
        $name = Name::from(Name::class);

        $namespace = $name->namespace();

        $this->assertEquals('PhUml\\Code', $namespace);
    }

    /** @test */
    function it_removes_its_namespace_when_it_is_converted_to_string()
    {
        $name = Name::from(Name::class);

        $nameOnly = $name->__toString();

        $this->assertEquals('Name', $nameOnly);
    }

    /** @test */
    function it_represents_a_definition_without_namespace_correctly()
    {
        $name = Name::from('plBase');

        $this->assertEquals('plBase', $name->__toString());
        $this->assertEmpty($name->namespace());
    }
}
