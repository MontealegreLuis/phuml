<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser\Raw;

use PHPUnit\Framework\TestCase;

class RawDefinitionsTest extends TestCase
{
    /** @test */
    function it_adds_a_class_definition()
    {
        $definitions = new RawDefinitions();

        $definitions->add(RawDefinition::class(['class' => 'MyClass']));

        $this->assertTrue($definitions->has('MyClass'));
    }

    /** @test */
    function it_adds_an_interface_definition()
    {
        $definitions = new RawDefinitions();

        $definitions->add(RawDefinition::interface(['interface' => 'MyInterface']));

        $this->assertTrue($definitions->has('MyInterface'));
    }

    /** @test */
    function it_adds_an_external_class()
    {
        $definitions = new RawDefinitions();

        $definitions->addExternalClass('MyClass');

        $class = $definitions->get('MyClass');
        $this->assertCount(0, $class->attributes());
        $this->assertCount(0, $class->methods());
        $this->assertCount(0, $class->interfaces());
        $this->assertNull($class->parent());
    }

    /** @test */
    function it_adds_an_external_interface()
    {
        $definitions = new RawDefinitions();

        $definitions->addExternalClass('MyInterface');

        $interface = $definitions->get('MyInterface');
        $this->assertCount(0, $interface->methods());
        $this->assertNull($interface->parent());
    }
}
