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
        $namedMethod = Method::public('namedMethod');

        $name = $namedMethod->name();

        $this->assertEquals('namedMethod', $name);
    }

    /** @test */
    function it_knows_its_visibility()
    {
        $publicMethod = Method::public('publicMethod');
        $protectedMethod = Method::protected('protectedMethod');
        $privateMethod = Method::private('privateMethod');

        $this->assertEquals(Visibility::public(), $publicMethod->modifier());
        $this->assertEquals(Visibility::protected(), $protectedMethod->modifier());
        $this->assertEquals(Visibility::private(), $privateMethod->modifier());
    }

    /** @test */
    function it_has_no_parameters_by_default()
    {
        $noParametersMethod = Method::public('noParametersMethod');

        $parameters = $noParametersMethod->parameters();

        $this->assertCount(0, $parameters);
    }

    /** @test */
    function it_knows_its_parameters()
    {
        $expectedParameters = [
            Variable::declaredWith('first'),
            Variable::declaredWith('second'),
        ];
        $methodWithParameters = Method::public('methodWithParameters', $expectedParameters);

        $parameters = $methodWithParameters->parameters();

        $this->assertEquals($expectedParameters, $parameters);
    }

    /** @test */
    function it_knows_it_is_a_constructor()
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
            Variable::declaredWith('$parameterOne'),
            Variable::declaredWith('$parameterTwoWithType', TypeDeclaration::from('int')),
        ]);

        $methodAsString = $methodWithParameters->__toString();

        $this->assertEquals(
        '#withParameters( $parameterOne, int $parameterTwoWithType )',
            $methodAsString
        );
    }
}
