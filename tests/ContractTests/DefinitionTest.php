<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Definition;
use PhUml\Code\Methods\Method;

abstract class DefinitionTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedDefinition = $this->definition();

        $name = $namedDefinition->name();

        $this->assertEquals('ADefinition', $name);
    }

    /** @test */
    function it_has_no_methods_by_default()
    {
        $noMethodsDefinition = $this->definition();

        $methods = $noMethodsDefinition->methods();

        $this->assertCount(0, $methods);
    }

    /** @test */
    function it_has_no_constants_by_default()
    {
        $noConstantsDefinition = $this->definition();

        $constants = $noConstantsDefinition->constants();

        $this->assertCount(0, $constants);
    }

    /** @test */
    function it_does_not_extends_another_definition_by_default()
    {
        $definitionWithoutParent = $this->definition();

        $hasParent = $definitionWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_methods()
    {
        $methods = [
            Method::public('methodOne'),
            Method::public('methodTwo'),
        ];
        $definitionWithMethods = $this->definition([] , $methods);

        $definitionMethods = $definitionWithMethods->methods();

        $this->assertEquals($methods, $definitionMethods);
    }

    /** @test */
    function it_knows_its_constants()
    {
        $constants = [
            new Constant('FIRST_CONSTANT'),
            new Constant('SECOND_CONSTANT'),
        ];
        $definitionWithConstants = $this->definition($constants);

        $definitionConstants = $definitionWithConstants->constants();

        $this->assertEquals($constants, $definitionConstants);
    }

    /** @test */
    function it_knows_its_parent()
    {
        $parent = $this->parent();
        $interfaceWithParent = $this->definition([], [], $parent);

        $parentClass = $interfaceWithParent->extends();

        $this->assertEquals($parent, $parentClass);
    }

    /** @test */
    function it_has_an_identifier()
    {
        $definition = $this->definition();

        $definitionId = $definition->identifier();

        $this->assertRegExp('/^[0-9A-Fa-f]{32}$/', $definitionId);
    }

    /** @test */
    function its_identifier_is_unique_per_object()
    {
        $definitionOne = $this->definition();
        $definitionTwo = $this->definition();

        $this->assertNotEquals($definitionOne->identifier(), $definitionTwo->identifier());
        $this->assertEquals($definitionOne->identifier(), $definitionOne->identifier());
        $this->assertEquals($definitionTwo->identifier(), $definitionTwo->identifier());
    }

    abstract protected function definition(
        array $constants = [],
        array $methods = [],
        Definition $parent = null
    ): Definition;

    abstract protected function parent(): Definition;
}
