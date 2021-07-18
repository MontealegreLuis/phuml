<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

final class CodebaseTest extends TestCase
{
    /** @test */
    function it_adds_a_new_class_definition()
    {
        $codebase = new Codebase();
        $class = A::classNamed('MyClass');

        $codebase->add($class);

        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
    }

    /** @test */
    function it_adds_a_new_interface_definition()
    {
        $codebase = new Codebase();
        $interface = A::interfaceNamed('MyInterface');

        $codebase->add($interface);

        $this->assertTrue($codebase->has($interface->name()));
        $this->assertEquals($interface, $codebase->get($interface->name()));
    }

    /** @test */
    function it_gets_all_the_definitions()
    {
        $codebase = new Codebase();
        $classA = A::classNamed('ClassA');
        $classB = A::classNamed('ClassB');
        $classC = A::classNamed('ClassC');
        $interfaceA = A::interfaceNamed('InterfaceA');
        $interfaceB = A::interfaceNamed('InterfaceB');

        $codebase->add($classC);
        $codebase->add($interfaceB);
        $codebase->add($classA);
        $codebase->add($interfaceA);
        $codebase->add($classB);

        $definitions = $codebase->definitions();

        $this->assertCount(5, $definitions);

        $this->assertArrayHasKey((string) $interfaceA->name(), $definitions);
        $this->assertEquals($interfaceA, $definitions[(string) $interfaceA->name()]);

        $this->assertArrayHasKey((string) $interfaceB->name(), $definitions);
        $this->assertEquals($interfaceB, $definitions[(string) $interfaceB->name()]);

        $this->assertArrayHasKey((string) $classA->name(), $definitions);
        $this->assertEquals($classA, $definitions[(string) $classA->name()]);

        $this->assertArrayHasKey((string) $classB->name(), $definitions);
        $this->assertEquals($classB, $definitions[(string) $classB->name()]);

        $this->assertArrayHasKey((string) $classC->name(), $definitions);
        $this->assertEquals($classC, $definitions[(string) $classC->name()]);
    }
}
