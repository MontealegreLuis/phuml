<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\HasConstants;
use PhUml\ContractTests\DefinitionTest;
use PhUml\ContractTests\WithConstantsTests;
use PhUml\TestBuilders\A;

final class InterfaceDefinitionTest extends DefinitionTest
{
    use WithConstantsTests;

    /** @test */
    function it_does_not_extends_another_definition_by_default()
    {
        $definitionWithoutParent = new InterfaceDefinition(new Name('WithoutParent'));

        $hasParent = $definitionWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_parent()
    {
        $parent = new InterfaceDefinition(new Name('ParentInterface'));
        $anotherParent = new InterfaceDefinition(new Name('AnotherParentInterface'));
        $interfaceWithParent = A::interface('WithParent')
            ->extending($parent->name(), $anotherParent->name())
            ->build();

        $parents = $interfaceWithParent->parents();

        $this->assertCount(2, $parents);
        $this->assertEquals([$parent->name(), $anotherParent->name()], $parents);
    }

    protected function definition(array $methods = []): Definition
    {
        return new InterfaceDefinition(new Name('ADefinition'), $methods);
    }

    protected function definitionWithConstants(array $constants = []): HasConstants
    {
        return new InterfaceDefinition(new Name('AnyClassDefinition'), [], $constants);
    }
}
