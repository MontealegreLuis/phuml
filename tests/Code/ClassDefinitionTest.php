<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\ContractTests\DefinitionTest;
use PhUml\TestBuilders\A;

class ClassDefinitionTest extends DefinitionTest
{
    /** @test */
    function it_has_by_default_no_attributes()
    {
        $noAttributesClass = new ClassDefinition('NoAttributesClass');

        $attributes = $noAttributesClass->attributes();

        $this->assertCount(0, $attributes);
    }

    /** @test */
    function it_does_not_implement_any_interface_by_default()
    {
        $noInterfacesClass = new ClassDefinition('NoInterfacesClass');

        $interfaces = $noInterfacesClass->interfaces();

        $this->assertCount(0, $interfaces);
    }

    /** @test */
    function it_knows_its_attributes()
    {
        $attributes = [
            Attribute::public('$firstAttribute'),
            Attribute::public('$secondAttribute'),
        ];

        $classWithAttributes = A::class('ClassWithAttributes')
            ->withAPublicAttribute('$firstAttribute')
            ->withAPublicAttribute('$secondAttribute')
            ->build()
        ;

        $classAttributes = $classWithAttributes->attributes();

        $this->assertEquals($attributes, $classAttributes);
    }

    /** @test */
    function it_has_access_to_its_constructor_parameters()
    {
        $firstParameter = A::parameter('$first')->build();
        $secondParameter = A::parameter('$second')->withType('float')->build();
        $class = A::class('ClassWithConstructor')
            ->withAPublicMethod('notAConstructor')
            ->withAPublicMethod('__construct', $firstParameter, $secondParameter)
            ->withAPublicMethod('NotAConstructorEither')
            ->build()
        ;

        $constructorParameters = $class->constructorParameters();

        $this->assertEquals($firstParameter, $constructorParameters[0]);
        $this->assertEquals($secondParameter, $constructorParameters[1]);
    }

    /** @test */
    function it_knows_its_constructor_has_no_parameters_if_no_constructor_is_specified()
    {
        $class = A::class('ClassWithoutConstructor')
            ->withAPublicMethod('notAConstructor')
            ->withAPublicMethod('notAConstructorEither')
            ->build()
        ;

        $constructorParameters = $class->constructorParameters();

        $this->assertCount(0, $constructorParameters);
    }

    /** @test */
    function it_knows_the_interfaces_it_implements()
    {
        $interfaces = [
            new InterfaceDefinition('InterfaceOne'),
            new InterfaceDefinition('InterfaceTwo'),
        ];
        $classWithInterfaces = A::class('ClassWithInterfaces')
            ->implementing(...$interfaces)
            ->build()
        ;

        $classInterfaces = $classWithInterfaces->interfaces();

        $this->assertEquals($interfaces, $classInterfaces);
    }

    /** @test */
    function it_does_not_extends_another_definition_by_default()
    {
        $definitionWithoutParent = new ClassDefinition('NoParentClass');

        $hasParent = $definitionWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_parent()
    {
        $parent = Name::from('ParentClass');
        $interfaceWithParent = A::class('WithParent')->extending($parent)->build();

        $parentClass = $interfaceWithParent->parent();

        $this->assertEquals($parent, $parentClass);
    }

    protected function definition(array $constants = [], array $methods = []): Definition
    {
        return new ClassDefinition('ADefinition', $constants, $methods);
    }
}
