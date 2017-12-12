<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plPhpClassTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedClass = new plPhpClass('NamedClass');

        $name = $namedClass->name;

        $this->assertEquals('NamedClass', $name);
    }

    /** @test */
    function it_has_by_default_no_attributes()
    {
        $noAttributesClass = new plPhpClass('NoAttributesClass');

        $attributes = $noAttributesClass->attributes;

        $this->assertCount(0, $attributes);
    }

    /** @test */
    function it_has_by_default_no_methods()
    {
        $noMethodsClass = new plPhpClass('NoMethodsClass');

        $methods = $noMethodsClass->functions;

        $this->assertCount(0, $methods);
    }

    /** @test */
    function it_does_not_implements_any_interface_by_default()
    {
        $noInterfacesClass = new plPhpClass('NoInterfacesClass');

        $interfaces = $noInterfacesClass->implements;

        $this->assertCount(0, $interfaces);
    }

    /** @test */
    function it_does_not_have_a_parent_class_by_default()
    {
        $noParentClass = new plPhpClass('NoParentClass');

        $parent = $noParentClass->extends;

        $this->assertNull($parent);
    }

    /** @test */
    function it_knows_its_attributes()
    {
        $attributes = [
            new plPhpAttribute('firstAttribute'),
            new plPhpAttribute('secondAttribute'),
        ];
        $classWithAttributes = new plPhpClass('ClassWithAttributes', $attributes);

        $classAttributes = $classWithAttributes->attributes;

        $this->assertEquals($attributes, $classAttributes);
    }

    /** @test */
    function it_knows_its_methods()
    {
        $methods = [
            new plPhpFunction('methodOne'),
            new plPhpFunction('methodTwo'),
        ];
        $classWithMethods = new plPhpClass('ClassWithMethods', [], $methods);

        $classMethods = $classWithMethods->functions;

        $this->assertEquals($methods, $classMethods);
    }

    /** @test */
    function it_knows_it_has_a_constructor()
    {
        $class = new plPhpClass('ClassWithConstructor', [], [
            new plPhpFunction('notAConstructor'),
            new plPhpFunction('__construct'),
            new plPhpFunction('notAConstructorEither'),
        ]);

        $this->assertTrue($class->hasConstructor());
    }

    /** @test */
    function it_knows_it_does_not_have_a_constructor()
    {
        $class = new plPhpClass('ClassWithConstructor', [], [
            new plPhpFunction('notAConstructor'),
            new plPhpFunction('notAConstructorEither'),
        ]);

        $this->assertFalse($class->hasConstructor());
    }

    /** @test */
    function it_has_access_to_its_constructor_parameters()
    {
        $parameters = [
            new plPhpVariable('first'),
            new plPhpVariable('second', 'float'),
        ];
        $class = new plPhpClass('ClassWithConstructor', [], [
            new plPhpFunction('notAConstructor'),
            new plPhpFunction('__construct', 'public', $parameters),
            new plPhpFunction('notAConstructorEither'),
        ]);

        $constructorParameters = $class->constructorParameters();

        $this->assertEquals($parameters, $constructorParameters);
    }

    /** @test */
    function it_knows_the_interfaces_it_implements()
    {
        $interfaces = [
            new plPhpInterface('InterfaceOne'),
            new plPhpInterface('InterfaceTwo'),
        ];
        $classWithInterfaces = new plPhpClass('ClassWithInterfaces', [], [], $interfaces);

        $classInterfaces = $classWithInterfaces->implements;

        $this->assertEquals($interfaces, $classInterfaces);
    }

    /** @test */
    function it_knows_its_parent_class()
    {
        $parent = new plPhpClass('ParentClass');
        $classWithParent = new plPhpClass('ClassWithParent', [], [], [], $parent);

        $parentClass = $classWithParent->extends;

        $this->assertEquals($parent, $parentClass);
    }

    /** @test */
    function it_has_an_identifier()
    {
        $class = new plPhpClass('ClassWithIdentifier');

        $classId = $class->identifier();

        $this->assertRegExp('/^[0-9A-Fa-f]{32}$/', $classId);
    }

    /** @test */
    function its_identifier_is_unique_per_object()
    {
        $classOne = new plPhpClass('ClassOne');
        $classTwo = new plPhpClass('ClassOne');

        $this->assertNotEquals($classOne->identifier(), $classTwo->identifier());
    }

    /** @test */
    function it_knows_if_it_has_a_parent_class()
    {
        $parentClass = new plPhpClass('ParentClass');
        $classWithParent = new plPhpClass('ClassWithParent', [], [], [], $parentClass);

        $hasParent = $classWithParent->hasParent();

        $this->assertTrue($hasParent);
    }
}
