<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class CodebaseTest extends TestCase
{
    /** @test */
    function it_adds_a_new_class_definition()
    {
        $codebase = new Codebase();
        $class = new ClassDefinition('MyClass');

        $codebase->add($class);

        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get('MyClass'));
    }

    /** @test */
    function it_adds_a_new_interface_definition()
    {
        $codebase = new Codebase();
        $interface = new InterfaceDefinition('MyInterface');

        $codebase->add($interface);

        $this->assertTrue($codebase->has($interface->name()));
        $this->assertEquals($interface, $codebase->get('MyInterface'));
    }

    /** @test */
    function it_gets_all_the_definitions()
    {
        $codebase = new Codebase();
        $classA = new ClassDefinition('ClassA');
        $classB = new ClassDefinition('ClassB');
        $classC = new ClassDefinition('ClassC');
        $interfaceA = new InterfaceDefinition('InterfaceA');
        $interfaceB = new InterfaceDefinition('InterfaceB');

        $codebase->add($classC);
        $codebase->add($interfaceB);
        $codebase->add($classA);
        $codebase->add($interfaceA);
        $codebase->add($classB);

        $definitions = $codebase->definitions();

        $this->assertCount(5, $definitions);

        $this->assertArrayHasKey((string)$interfaceA->name(), $definitions);
        $this->assertEquals($interfaceA, $definitions[(string)$interfaceA->name()]);

        $this->assertArrayHasKey((string)$interfaceB->name(), $definitions);
        $this->assertEquals($interfaceB, $definitions[(string)$interfaceB->name()]);

        $this->assertArrayHasKey((string)$classA->name(), $definitions);
        $this->assertEquals($classA, $definitions[(string)$classA->name()]);

        $this->assertArrayHasKey((string)$classB->name(), $definitions);
        $this->assertEquals($classB, $definitions[(string)$classB->name()]);

        $this->assertArrayHasKey((string)$classC->name(), $definitions);
        $this->assertEquals($classC, $definitions[(string)$classC->name()]);
    }
}
