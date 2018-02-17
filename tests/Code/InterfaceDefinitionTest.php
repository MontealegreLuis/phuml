<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\ContractTests\DefinitionTest;
use PhUml\TestBuilders\A;

class InterfaceDefinitionTest extends DefinitionTest
{
    /** @test */
    function it_does_not_extends_another_definition_by_default()
    {
        $definitionWithoutParent = new InterfaceDefinition('WithoutParent');

        $hasParent = $definitionWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_parent()
    {
        $parent = new InterfaceDefinition('ParentInterface');
        $interfaceWithParent = A::interface('WithParent')->extending($parent)->build();

        $parentClass = $interfaceWithParent->extends();

        $this->assertEquals($parent, $parentClass);
    }

    protected function definition(array $constants = [], array $methods = []): Definition
    {
        return new InterfaceDefinition('ADefinition', $constants, $methods);
    }
}
