<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

class InterfaceDefinitionTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedInterface = new InterfaceDefinition('NamedInterface');

        $name = $namedInterface->name();

        $this->assertEquals('NamedInterface', $name);
    }

    /** @test */
    function it_has_no_methods_by_default()
    {
        $noMethodsInterface = new InterfaceDefinition('WithNoMethods');

        $methods = $noMethodsInterface->methods();

        $this->assertCount(0, $methods);
    }

    /** @test */
    function it_does_not_extends_another_interface_by_default()
    {
        $interfaceWithoutParent = new InterfaceDefinition('InterfacesWithoutParent');

        $hasParent = $interfaceWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_methods()
    {
        $interfaceWithMethods = A::interface('InterfaceWithMethods')
            ->withAPublicMethod('firstMethod')
            ->withAPublicMethod('secondMethod')
            ->build()
        ;

        $interfaceMethods = $interfaceWithMethods->methods();

        $this->assertEquals('firstMethod', $interfaceMethods[0]->name());
        $this->assertEquals('secondMethod', $interfaceMethods[1]->name());
    }

    /** @test */
    function it_knows_its_parent_interface()
    {
        $parent = new InterfaceDefinition('ParentInterface');
        $interfaceWithParent = A::interface('InterfaceWithParent')
            ->withParent($parent)
            ->build()
        ;

        $parentClass = $interfaceWithParent->extends();

        $this->assertEquals($parent, $parentClass);
    }

    /** @test */
    function it_has_an_identifier()
    {
        $interface = new InterfaceDefinition('InterfaceWithIdentifier');

        $interfaceId = $interface->identifier();

        $this->assertRegExp('/^[0-9A-Fa-f]{32}$/', $interfaceId);
    }

    /** @test */
    function its_identifier_is_unique_per_object()
    {
        $interfaceOne = new InterfaceDefinition('InterfaceOne');
        $interfaceTwo = new InterfaceDefinition('InterfaceOne');

        $this->assertNotEquals($interfaceOne->identifier(), $interfaceTwo->identifier());
        $this->assertEquals($interfaceOne->identifier(), $interfaceOne->identifier());
        $this->assertEquals($interfaceTwo->identifier(), $interfaceTwo->identifier());
    }
}
