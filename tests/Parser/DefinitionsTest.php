<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;

class DefinitionsTest extends TestCase
{
    /** @test */
    function it_adds_a_class_definition()
    {
        $definitions = new Definitions();

        $definitions->add(['class' => 'MyClass']);

        $this->assertTrue($definitions->has('MyClass'));
    }

    /** @test */
    function it_adds_an_interface_definition()
    {
        $definitions = new Definitions();

        $definitions->add(['interface' => 'MyInterface']);

        $this->assertTrue($definitions->has('MyInterface'));
    }

    /** @test */
    function it_adds_an_external_class()
    {
        $definitions = new Definitions();

        $definitions->addExternalClass('MyClass');

        $class = $definitions->get('MyClass');
        $this->assertCount(0, $class['attributes']);
        $this->assertCount(0, $class['methods']);
        $this->assertCount(0, $class['implements']);
        $this->assertNull($class['extends']);
    }

    /** @test */
    function it_adds_an_external_interface()
    {
        $definitions = new Definitions();

        $definitions->addExternalClass('MyInterface');

        $interface = $definitions->get('MyInterface');
        $this->assertCount(0, $interface['methods']);
        $this->assertNull($interface['extends']);
    }

    /** @test */
    function it_recognizes_a_class()
    {
        $definitions = new Definitions();

        $isClass = $definitions->isClass(['class' => 'MyClass']);

        $this->assertTrue($isClass);
    }

    /** @test */
    function it_recognizes_an_interface()
    {
        $definitions = new Definitions();

        $isInterface = $definitions->isInterface(['interface' => 'MyInterface']);

        $this->assertTrue($isInterface);
    }

    /** @test */
    function it_knows_the_interfaces_implemented_by_a_class()
    {
        $definitions = new Definitions();
        $implemented = ['InterfaceOne', 'InterfaceTwo'];

        $interfaces = $definitions->interfaces([
            'interface' => 'MyInterface',
            'implements' => $implemented
        ]);

        $this->assertEquals($implemented, $interfaces);
    }

    /** @test */
    function it_knows_if_a_definition_has_a_parent()
    {
        $definitions = new Definitions();
        $withParent = ['class' => 'AClass', 'extends' => 'Parent'];
        $noParent = ['interface' => 'AnInterface'];

        $this->assertTrue($definitions->hasParent($withParent));
        $this->assertFalse($definitions->hasParent($noParent));
    }

    /** @test */
    function it_gets_the_parent_of_a_definition()
    {
        $definitions = new Definitions();
        $withParent = ['class' => 'AClass', 'extends' => 'Parent'];
        $noParent = ['interface' => 'AnInterface'];

        $this->assertEquals('Parent', $definitions->parent($withParent));
        $this->assertNull($definitions->parent($noParent));
    }
}
