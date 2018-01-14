<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedFunction = Method::public('namedFunction');

        $name = $namedFunction->name;

        $this->assertEquals('namedFunction', $name);
    }

    /** @test */
    function it_knows_its_visibility()
    {
        $publicFunction = Method::public('publicFunction');
        $protectedFunction = Method::protected('protectedFunction');
        $privateFunction = Method::private('privateFunction');

        $this->assertEquals('public', $publicFunction->modifier);
        $this->assertEquals('protected', $protectedFunction->modifier);
        $this->assertEquals('private', $privateFunction->modifier);
    }

    /** @test */
    function it_has_no_parameters_by_default()
    {
        $noParametersFunction = Method::public('noParametersFunction');

        $parameters = $noParametersFunction->params;

        $this->assertCount(0, $parameters);
    }

    /** @test */
    function it_knows_its_parameters()
    {
        $expectedParameters = [
            Variable::declaredWith('first'),
            Variable::declaredWith('second'),
        ];
        $functionWithParameters = Method::public('functionWithParameters', $expectedParameters);

        $parameters = $functionWithParameters->params;

        $this->assertEquals($expectedParameters, $parameters);
    }

    /** @test */
    function it_knows_if_it_is_a_constructor()
    {
        $constructor = Method::public('__construct');

        $isConstructor = $constructor->isConstructor();

        $this->assertTrue($isConstructor);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $method = Method::public('method');

        $methodAsString = $method->__toString();

        $this->assertEquals('+method()', $methodAsString);
    }

    /** @test */
    function its_string_representation_includes_its_parameters()
    {
        $methodWithParameters = Method::protected('withParameters', [
            Variable::declaredWith('parameterOne'),
            Variable::declaredWith('parameterTwoWithType', TypeDeclaration::from('int')),
        ]);

        $methodAsString = $methodWithParameters->__toString();

        $this->assertEquals(
        '#withParameters( parameterOne, int parameterTwoWithType )',
            $methodAsString
        );
    }
}
