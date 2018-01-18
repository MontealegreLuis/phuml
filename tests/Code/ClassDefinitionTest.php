<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class ClassDefinitionTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedClass = new ClassDefinition('NamedClass');

        $name = $namedClass->name();

        $this->assertEquals('NamedClass', $name);
    }

    /** @test */
    function it_has_by_default_no_attributes()
    {
        $noAttributesClass = new ClassDefinition('NoAttributesClass');

        $attributes = $noAttributesClass->attributes();

        $this->assertCount(0, $attributes);
    }

    /** @test */
    function it_has_by_default_no_methods()
    {
        $noMethodsClass = new ClassDefinition('NoMethodsClass');

        $methods = $noMethodsClass->methods();

        $this->assertCount(0, $methods);
    }

    /** @test */
    function it_does_not_implement_any_interface_by_default()
    {
        $noInterfacesClass = new ClassDefinition('NoInterfacesClass');

        $interfaces = $noInterfacesClass->implements();

        $this->assertCount(0, $interfaces);
    }

    /** @test */
    function it_does_not_have_a_parent_class_by_default()
    {
        $noParentClass = new ClassDefinition('NoParentClass');

        $hasParent = $noParentClass->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_attributes()
    {
        $attributes = [
            Attribute::public('firstAttribute'),
            Attribute::public('secondAttribute'),
        ];
        $classWithAttributes = new ClassDefinition('ClassWithAttributes', $attributes);

        $classAttributes = $classWithAttributes->attributes();

        $this->assertEquals($attributes, $classAttributes);
    }

    /** @test */
    function it_knows_its_methods()
    {
        $methods = [
            Method::public('methodOne'),
            Method::public('methodTwo'),
        ];
        $classWithMethods = new ClassDefinition('ClassWithMethods', [], $methods);

        $classMethods = $classWithMethods->methods();

        $this->assertEquals($methods, $classMethods);
    }

    /** @test */
    function it_knows_it_has_a_constructor()
    {
        $class = new ClassDefinition('ClassWithConstructor', [], [
            Method::public('notAConstructor'),
            Method::public('__construct'),
            Method::public('notAConstructorEither'),
        ]);

        $this->assertTrue($class->hasConstructor());
    }

    /** @test */
    function it_knows_it_does_not_have_a_constructor()
    {
        $class = new ClassDefinition('ClassWithConstructor', [], [
            Method::public('notAConstructor'),
            Method::public('notAConstructorEither'),
        ]);

        $this->assertFalse($class->hasConstructor());
    }

    /** @test */
    function it_has_access_to_its_constructor_parameters()
    {
        $parameters = [
            Variable::declaredWith('first'),
            Variable::declaredWith('second', TypeDeclaration::from('float')),
        ];
        $class = new ClassDefinition('ClassWithConstructor', [], [
            Method::public('notAConstructor'),
            Method::public('__construct', $parameters),
            Method::public('notAConstructorEither'),
        ]);

        $constructorParameters = $class->constructorParameters();

        $this->assertEquals($parameters, $constructorParameters);
    }

    /** @test */
    function it_knows_its_constructor_has_no_parameters_if_no_constructor_is_specified()
    {
        $class = new ClassDefinition('ClassWithConstructor', [], [
            Method::public('notAConstructor'),
            Method::public('notAConstructorEither'),
        ]);

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
        $classWithInterfaces = new ClassDefinition('ClassWithInterfaces', [], [], $interfaces);

        $classInterfaces = $classWithInterfaces->implements();

        $this->assertEquals($interfaces, $classInterfaces);
    }

    /** @test */
    function it_knows_its_parent_class()
    {
        $parent = new ClassDefinition('ParentClass');
        $classWithParent = new ClassDefinition('ClassWithParent', [], [], [], $parent);

        $parentClass = $classWithParent->extends();

        $this->assertEquals($parent, $parentClass);
    }

    /** @test */
    function it_has_an_identifier()
    {
        $class = new ClassDefinition('ClassWithIdentifier');

        $classId = $class->identifier();

        $this->assertRegExp('/^[0-9A-Fa-f]{32}$/', $classId);
    }

    /** @test */
    function its_identifier_is_unique_per_object()
    {
        $classOne = new ClassDefinition('ClassOne');
        $classTwo = new ClassDefinition('ClassOne');

        $this->assertNotEquals($classOne->identifier(), $classTwo->identifier());
        $this->assertEquals($classOne->identifier(), $classOne->identifier());
        $this->assertEquals($classTwo->identifier(), $classTwo->identifier());
    }
}
