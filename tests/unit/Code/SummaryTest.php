<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

final class SummaryTest extends TestCase
{
    /** @test */
    function it_generates_a_summary_from_an_empty_codebase()
    {
        $codebase = new Codebase();

        $summary = Summary::from($codebase);

        $this->assertSame(0, $summary->interfaceCount());
        $this->assertSame(0, $summary->classCount());
        $this->assertSame(0, $summary->publicFunctionCount());
        $this->assertSame(0, $summary->publicPropertyCount());
        $this->assertSame(0, $summary->publicTypedProperties());
        $this->assertSame(0, $summary->protectedFunctionCount());
        $this->assertSame(0, $summary->protectedPropertyCount());
        $this->assertSame(0, $summary->protectedTypedProperties());
        $this->assertSame(0, $summary->privateFunctionCount());
        $this->assertSame(0, $summary->privatePropertyCount());
        $this->assertSame(0, $summary->privateTypedProperties());
        $this->assertSame(0, $summary->functionCount());
        $this->assertSame(0, $summary->propertiesCount());
        $this->assertSame(0, $summary->typedPropertiesCount());
        $this->assertSame(0.0, $summary->propertiesPerClass());
        $this->assertSame(0.0, $summary->functionsPerClass());
    }

    /** @test */
    function it_counts_protected_typed_properties()
    {
        $codebase = new Codebase();
        $classWith2TypedProperties = A::class('ClassA')
            ->withAProtectedProperty('aString', 'string')
            ->withAProtectedProperty('aFloat', 'float')
            ->withAProtectedProperty('aMixed')
            ->build();
        $classWith3TypedProperties = A::class('ClassB')
            ->withAProtectedProperty('aString', 'string')
            ->withAProtectedProperty('aFloat', 'float')
            ->withAProtectedProperty('aBoolean', 'bool')
            ->build();
        $classWith1TypedProperty = A::class('ClassC')
            ->withAProtectedProperty('aString', 'string')
            ->withAPrivateProperty('aFloat', 'float')
            ->withAPublicProperty('aBoolean', 'bool')
            ->build();
        $codebase->add($classWith2TypedProperties);
        $codebase->add($classWith3TypedProperties);
        $codebase->add($classWith1TypedProperty);

        $summary = Summary::from($codebase);

        $this->assertSame(6, $summary->protectedTypedProperties());
    }

    /** @test */
    function it_generates_a_summary_from_a_codebase()
    {
        $parentClass = A::class('ParentClass')
            ->withAProtectedProperty('$property')
            ->withAPublicProperty('$value', 'float')
            ->withAPublicProperty('isValid')
            ->withAProtectedMethod('getAttribute')
            ->withAPrivateMethod('privateAction')
            ->withAConstant('TEST')
            ->build();
        $parentInterface = A::interface('ParentInterface')
            ->withAPublicMethod('dance')
            ->withAConstant('TYPED_TEST', 'float')
            ->build();
        $interface = A::interface('SomeAbility')
            ->withAPublicMethod('fly')
            ->extending($parentInterface->name())
            ->build();

        $codebase = new Codebase();
        $codebase->add($parentClass);
        $codebase->add($parentInterface);
        $codebase->add($interface);
        $codebase->add(A::class('ChildClass')
            ->withAPrivateProperty('$name', 'string')
            ->withAPrivateProperty('$salary')
            ->withAProtectedProperty('$age', 'int')
            ->withAPublicMethod('getName')
            ->withAPublicMethod('getAge')
            ->implementing($interface->name())
            ->extending($parentClass->name())
            ->build());

        $summary = Summary::from($codebase);

        $this->assertSame(2, $summary->interfaceCount());
        $this->assertSame(2, $summary->classCount());
        $this->assertSame(4, $summary->publicFunctionCount());
        $this->assertSame(2, $summary->publicPropertyCount());
        $this->assertSame(1, $summary->publicTypedProperties());
        $this->assertSame(1, $summary->protectedFunctionCount());
        $this->assertSame(2, $summary->protectedPropertyCount());
        $this->assertSame(1, $summary->protectedTypedProperties());
        $this->assertSame(1, $summary->privateFunctionCount());
        $this->assertSame(2, $summary->privatePropertyCount());
        $this->assertSame(1, $summary->privateTypedProperties());
        $this->assertSame(6, $summary->functionCount());
        $this->assertSame(6, $summary->propertiesCount());
        $this->assertSame(3, $summary->typedPropertiesCount());
        $this->assertSame(3.0, $summary->propertiesPerClass());
        $this->assertSame(3.0, $summary->functionsPerClass());
    }

    /** @test */
    function it_calculates_average_of_properties_per_class()
    {
        $codebase = new Codebase();
        $classWith3Properties = A::class('ClassA')
            ->withAProtectedProperty('aString', 'string')
            ->withAProtectedProperty('aFloat', 'float')
            ->withAProtectedProperty('aMixed')
            ->build();
        $classWith2Properties = A::class('ClassB')
            ->withAProtectedProperty('aString', 'string')
            ->withAProtectedProperty('aBoolean', 'bool')
            ->build();
        $classWith5Properties = A::class('ClassC')
            ->withAProtectedProperty('aString', 'string')
            ->withAPrivateProperty('aFloat', 'float')
            ->withAPublicProperty('aBoolean', 'bool')
            ->withAPublicProperty('anArray')
            ->withAPublicProperty('anObject')
            ->build();
        $codebase->add($classWith3Properties);
        $codebase->add($classWith2Properties);
        $codebase->add($classWith5Properties);

        $summary = Summary::from($codebase);

        $this->assertSame(3.33, $summary->propertiesPerClass());
    }

    /** @test */
    function it_calculates_average_of_methods_per_class()
    {
        $codebase = new Codebase();
        $classWith3Methods = A::class('ClassA')
            ->withAProtectedMethod('methodA')
            ->withAProtectedMethod('methodB')
            ->withAProtectedMethod('methodC')
            ->build();
        $classWith2Methods = A::class('ClassB')
            ->withAProtectedMethod('methodA')
            ->withAProtectedMethod('methodB')
            ->build();
        $classWith5Methods = A::class('ClassC')
            ->withAProtectedMethod('methodA')
            ->withAPrivateMethod('methodB')
            ->withAPublicMethod('methodC')
            ->withAPublicMethod('methodD')
            ->withAPublicMethod('methodE')
            ->build();
        $codebase->add($classWith3Methods);
        $codebase->add($classWith2Methods);
        $codebase->add($classWith5Methods);

        $summary = Summary::from($codebase);

        $this->assertSame(3.33, $summary->functionsPerClass());
    }
}
