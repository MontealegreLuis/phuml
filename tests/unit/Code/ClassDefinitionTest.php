<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use BadMethodCallException;
use PhUml\Code\Properties\HasConstants;
use PhUml\Code\Properties\HasProperties;
use PhUml\Code\Properties\Property;
use PhUml\ContractTests\DefinitionTest;
use PhUml\ContractTests\WithConstantsTests;
use PhUml\ContractTests\WithPropertiesTests;
use PhUml\TestBuilders\A;

final class ClassDefinitionTest extends DefinitionTest
{
    use WithConstantsTests;
    use WithPropertiesTests;

    /** @test */
    function it_does_not_implement_any_interface_by_default()
    {
        $noInterfacesClass = new ClassDefinition(new Name('NoInterfacesClass'));

        $interfaces = $noInterfacesClass->interfaces();

        $this->assertCount(0, $interfaces);
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
            ->build();

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
            ->build();

        $constructorParameters = $class->constructorParameters();

        $this->assertCount(0, $constructorParameters);
    }

    /** @test */
    function it_knows_the_interfaces_it_implements()
    {
        $interfaces = [
            new Name('InterfaceOne'),
            new Name('InterfaceTwo'),
        ];
        $classWithInterfaces = A::class('ClassWithInterfaces')
            ->implementing(...$interfaces)
            ->build();

        $classInterfaces = $classWithInterfaces->interfaces();

        $this->assertEquals($interfaces, $classInterfaces);
    }

    /** @test */
    function it_does_not_extends_another_definition_by_default()
    {
        $definitionWithoutParent = new ClassDefinition(new Name('NoParentClass'));

        $hasParent = $definitionWithoutParent->hasParent();

        $this->assertFalse($hasParent);
    }

    /** @test */
    function it_knows_its_parent()
    {
        $parent = new Name('ParentClass');
        $interfaceWithParent = A::class('WithParent')->extending($parent)->build();

        $parentClass = $interfaceWithParent->parent();

        $this->assertEquals($parent, $parentClass);
    }

    /** @test */
    function it_fails_to_get_its_parent_class_if_none_exist()
    {
        $interfaceWithParent = A::class('WithoutParent')->build();

        $this->expectException(BadMethodCallException::class);
        $interfaceWithParent->parent();
    }

    /** @test */
    function it_knows_if_it_is_an_attribute_class()
    {
        $attributeClass = new ClassDefinition(new Name('ADefinition'), isAttribute: true);
        $regularClass = new ClassDefinition(new Name('ADefinition'));

        $this->assertTrue($attributeClass->isAttribute());
        $this->assertFalse($regularClass->isAttribute());
    }

    protected function definition(array $methods = []): Definition
    {
        return new ClassDefinition(new Name('ADefinition'), $methods);
    }

    protected function definitionWithConstants(array $constants = []): HasConstants
    {
        return new ClassDefinition(new Name('AnyClassDefinition'), [], $constants);
    }

    /** @param Property[] $properties */
    protected function definitionWithProperties(array $properties = []): HasProperties
    {
        return new ClassDefinition(new Name('AClassWithProperties'), [], [], null, $properties);
    }
}
