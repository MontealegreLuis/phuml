<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Definition;
use PhUml\Code\Methods\Method;
use PhUml\TestBuilders\A;

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
            A::method('methodOne')->public(),
            A::method('methodTwo')->public(),
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

        $this->assertEquals((string) $definition->name(), $definitionId);
    }

    /** @param Method[] $methods */
    abstract protected function definition(array $methods = []): Definition;
}
