<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plPhpFunctionTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedFunction = new plPhpFunction('namedFunction');

        $name = $namedFunction->name;

        $this->assertEquals('namedFunction', $name);
    }

    /** @test */
    function it_is_public_by_default()
    {
        $publicFunction = new plPhpFunction('publicFunction');

        $modifier = $publicFunction->modifier;

        $this->assertEquals('public', $modifier);
    }

    /** @test */
    function it_has_no_parameters_by_default()
    {
        $noParametersFunction = new plPhpFunction('noParametersFunction');

        $parameters = $noParametersFunction->params;

        $this->assertCount(0, $parameters);
    }

    /** @test */
    function it_knows_its_parameters()
    {
        $expectedParameters = [
            new plPhpVariable('first'),
            new plPhpVariable('second'),
        ];
        $functionWithParameters = new plPhpFunction('functionWithParameters', 'public', $expectedParameters);

        $parameters = $functionWithParameters->params;

        $this->assertEquals($expectedParameters, $parameters);
    }

    /** @test */
    function it_knows_if_it_is_a_constructor()
    {
        $constructor = new plPhpFunction('__construct');

        $isConstructor = $constructor->isConstructor();

        $this->assertTrue($isConstructor);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $method = new plPhpFunction('method');

        $methodAsString = $method->__toString();

        $this->assertEquals('+method()', $methodAsString);
    }

    /** @test */
    function its_string_representation_includes_its_parameters()
    {
        $methodWithParameters = new plPhpFunction('withParameters', 'protected', [
            new plPhpVariable('parameterOne'),
            new plPhpVariable('parameterTwoWithType', 'int'),
        ]);

        $methodAsString = $methodWithParameters->__toString();

        $this->assertEquals(
        '#withParameters( parameterOne, int parameterTwoWithType )',
            $methodAsString
        );
    }
}
