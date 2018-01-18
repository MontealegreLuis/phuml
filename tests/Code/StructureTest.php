<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class StructureTest extends TestCase
{
    /** @test */
    function it_adds_a_new_class_definition()
    {
        $structure = new Structure();
        $class = new ClassDefinition('MyClass');

        $structure->addClass($class);

        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_adds_a_new_interface_definition()
    {
        $structure = new Structure();
        $interface = new InterfaceDefinition('MyInterface');

        $structure->addInterface($interface);

        $this->assertTrue($structure->has('MyInterface'));
        $this->assertEquals($interface, $structure->get('MyInterface'));
    }

    /** @test */
    function it_gets_all_the_definitions()
    {
        $structure = new Structure();
        $classA = new ClassDefinition('ClassA');
        $classB = new ClassDefinition('ClassB');
        $classC = new ClassDefinition('ClassC');
        $interfaceA = new InterfaceDefinition('InterfaceA');
        $interfaceB = new InterfaceDefinition('InterfaceB');

        $structure->addClass($classC);
        $structure->addInterface($interfaceB);
        $structure->addClass($classA);
        $structure->addInterface($interfaceA);
        $structure->addClass($classB);

        $definitions = $structure->definitions();

        $this->assertCount(5, $definitions);

        $this->assertArrayHasKey($interfaceA->name(), $definitions);
        $this->assertEquals($interfaceA, $definitions[$interfaceA->name()]);

        $this->assertArrayHasKey($interfaceB->name(), $definitions);
        $this->assertEquals($interfaceB, $definitions[$interfaceB->name()]);

        $this->assertArrayHasKey($classA->name(), $definitions);
        $this->assertEquals($classA, $definitions[$classA->name()]);

        $this->assertArrayHasKey($classB->name(), $definitions);
        $this->assertEquals($classB, $definitions[$classB->name()]);

        $this->assertArrayHasKey($classC->name(), $definitions);
        $this->assertEquals($classC, $definitions[$classC->name()]);
    }
}
