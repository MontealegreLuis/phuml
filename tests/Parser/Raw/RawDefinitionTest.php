<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PHPUnit\Framework\TestCase;

class RawDefinitionTest extends TestCase
{
    /** @test */
    function it_knows_it_has_a_parent()
    {
        $withParent = RawDefinition::class(['class' => 'AClass', 'extends' => 'Parent']);
        $noParent = RawDefinition::interface(['interface' => 'AnInterface']);

        $this->assertTrue($withParent->hasParent());
        $this->assertFalse($noParent->hasParents());
    }

    /** @test */
    function it_knows_its_parent_name()
    {
        $withParent = RawDefinition::class(['class' => 'AClass', 'extends' => 'Parent']);
        $noParent = RawDefinition::interface(['interface' => 'AnInterface']);

        $this->assertEquals('Parent', $withParent->parent());
        $this->assertEmpty($noParent->parents());
    }

    /** @test */
    function it_knows_the_interfaces_it_implements()
    {
        $implemented = ['InterfaceOne', 'InterfaceTwo'];
        $class = RawDefinition::class([
            'class' => 'MyInterface',
            'implements' => $implemented
        ]);

        $interfaces = $class->interfaces();

        $this->assertEquals($implemented, $interfaces);
    }

    /** @test */
    function it_recognizes_an_interface()
    {
        $interface = RawDefinition::interface(['interface' => 'MyInterface']);

        $isInterface = $interface->isInterface();

        $this->assertTrue($isInterface);
    }

    /** @test */
    function it_recognizes_a_class()
    {
        $class = RawDefinition::class(['class' => 'MyClass']);

        $isClass = $class->isClass();

        $this->assertTrue($isClass);
    }
}
