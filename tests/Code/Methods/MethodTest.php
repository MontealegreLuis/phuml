<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
use PhUml\ContractTests\WithVisibilityTests;

class MethodTest extends TestCase
{
    use WithVisibilityTests;

    /** @test */
    function it_knows_if_it_is_abstract()
    {
        $abstractMethod = AbstractMethod::public('abstractMethod');
        $nonAbstractMethod = Method::public('nonAbstractMethod');

        $this->assertTrue($abstractMethod->isAbstract());
        $this->assertFalse($nonAbstractMethod->isAbstract());
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
        '#withParameters( $parameterOne, $parameterTwoWithType: int )',
            $methodAsString
        );
    }

    protected function publicMember(): HasVisibility
    {
        return Method::public('publicMethod');
    }

    protected function protectedMember(): HasVisibility
    {
        return Method::protected('protectedMethod');
    }

    protected function privateMember(): HasVisibility
    {
        return Method::private('privateMethod');
    }
}
