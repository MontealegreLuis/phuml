<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\ContractTests\WithVisibilityTests;
use PhUml\TestBuilders\A;

final class MethodTest extends TestCase
{
    use WithVisibilityTests;

    /** @test */
    function it_knows_if_it_is_abstract()
    {
        $abstractMethod = A::method('abstractMethod')->public()->abstract()->build();
        $nonAbstractMethod = A::method('nonAbstractMethod')->public()->build();

        $this->assertTrue($abstractMethod->isAbstract());
        $this->assertFalse($nonAbstractMethod->isAbstract());
    }

    /** @test */
    function it_has_no_parameters_by_default()
    {
        $noParametersMethod = A::method('noParametersMethod')->public()->build();

        $parameters = $noParametersMethod->parameters();

        $this->assertCount(0, $parameters);
    }

    /** @test */
    function it_knows_its_parameters()
    {
        $expectedParameters = [
            A::parameter('first')->build(),
            A::parameter('second')->build(),
        ];
        $methodWithParameters = A::method('methodWithParameters')
            ->public()
            ->withParameters(...$expectedParameters)
            ->build();

        $parameters = $methodWithParameters->parameters();

        $this->assertEquals($expectedParameters, $parameters);
    }

    /** @test */
    function it_knows_it_is_a_constructor()
    {
        $constructor = A::method('__construct')->public()->build();

        $isConstructor = $constructor->isConstructor();

        $this->assertTrue($isConstructor);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $method = A::method('method')->public()->build();

        $methodAsString = $method->__toString();

        $this->assertEquals('+method()', $methodAsString);
    }

    /** @test */
    function it_is_a_concrete_method_by_default()
    {
        $method = new Method(
            'aMethod',
            Visibility::public(),
            TypeDeclaration::absent(),
        );

        $isAbstract = $method->isAbstract();

        $this->assertFalse($isAbstract);
    }

    /** @test */
    function it_is_an_instance_method_by_default()
    {
        $method = new Method(
            'aMethod',
            Visibility::public(),
            TypeDeclaration::absent(),
        );

        $isStatic = $method->isStatic();

        $this->assertFalse($isStatic);
    }

    /** @test */
    function its_string_representation_includes_its_visibility_its_parameters_and_its_return_type()
    {
        $method =A::method('withParameters')
            ->protected()
            ->withParameters(
                A::parameter('$parameterOne')->build(),
                A::parameter('$parameterTwoWithType')->withType('int')->build()
            )
            ->withReturnType('SplStack')
            ->build();

        $methodAsString = $method->__toString();

        $this->assertEquals(
            '#withParameters($parameterOne, $parameterTwoWithType: int): SplStack',
            $methodAsString
        );
    }

    protected function publicMember(): HasVisibility
    {
        return A::method('publicMethod')->public()->build();
    }

    protected function protectedMember(): HasVisibility
    {
        return A::method('protectedMethod')->protected()->build();
    }

    protected function privateMember(): HasVisibility
    {
        return A::method('privateMethod')->private()->build();
    }
}
