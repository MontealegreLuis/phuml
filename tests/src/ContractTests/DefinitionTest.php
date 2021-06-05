<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PHPUnit\Framework\TestCase;
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
    function it_knows_its_methods()
    {
        $methods = [
            Method::public('methodOne'),
            Method::public('methodTwo'),
        ];
        $definitionWithMethods = $this->definition($methods);

        $definitionMethods = $definitionWithMethods->methods();

        $this->assertEquals($methods, $definitionMethods);
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

    /** @param \PhUml\Code\Methods\Method[] */
    abstract protected function definition(array $methods = []): Definition;
}
