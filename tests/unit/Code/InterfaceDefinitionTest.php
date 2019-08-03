<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\HasConstants;
use PhUml\ContractTests\DefinitionTest;
use PhUml\ContractTests\WithConstantsTests;
use PhUml\TestBuilders\A;

class InterfaceDefinitionTest extends DefinitionTest
{
    use WithConstantsTests;

    /** @test */
    function it_does_not_extends_another_definition_by_default()
    {
        $definitionWithoutParent = new InterfaceDefinition(Name::from('WithoutParent'));

        $hasParent = $definitionWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_parent()
    {
        $parent = new InterfaceDefinition(Name::from('ParentInterface'));
        $anotherParent = new InterfaceDefinition(Name::from('AnotherParentInterface'));
        $interfaceWithParent = A::interface('WithParent')
            ->extending($parent->name(), $anotherParent->name())
            ->build();

        $parents = $interfaceWithParent->parents();

        $this->assertCount(2, $parents);
        $this->assertEquals([$parent->name(), $anotherParent->name()], $parents);
    }

    protected function definition(array $methods = []): Definition
    {
        return new InterfaceDefinition(Name::from('ADefinition'), $methods);
    }

    protected function definitionWithConstants(array $constants = []): HasConstants
    {
        return new InterfaceDefinition(Name::from('AnyClassDefinition'), [], $constants);
    }
}
