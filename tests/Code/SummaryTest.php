<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

class SummaryTest extends TestCase
{
    /** @test */
    function it_generates_a_summary_from_a_code_structure()
    {
        $parentClass = A::class('ParentClass')
            ->withAProtectedAttribute('$attribute')
            ->withAPublicAttribute('$value', 'float')
            ->withAPublicAttribute('isValid')
            ->withAProtectedMethod('getAttribute')
            ->withAPrivateMethod('privateAction')
            ->build();
        $parentInterface = A::interface('ParentInterface')
            ->withAPublicMethod('dance')
            ->build();
        $interface = A::interface('SomeAbility')
            ->withAPublicMethod('fly')
            ->extending($parentInterface)
            ->build();

        $structure = new Structure();
        $structure->addClass($parentClass);
        $structure->addInterface($parentInterface);
        $structure->addInterface($interface);
        $structure->addClass(A::class('ChildClass')
            ->withAPrivateAttribute('$name', 'string')
            ->withAPrivateAttribute('$salary')
            ->withAProtectedAttribute('$age', 'int')
            ->withAPublicMethod('getName')
            ->withAPublicMethod('getAge')
            ->implementing($interface)
            ->extending($parentClass)
            ->build());
        $summary = new Summary();

        $summary->from($structure);

        $this->assertEquals(2, $summary->interfaceCount());
        $this->assertEquals(2, $summary->classCount());
        $this->assertEquals(4, $summary->publicFunctionCount());
        $this->assertEquals(2, $summary->publicAttributeCount());
        $this->assertEquals(1, $summary->publicTypedAttributes());
        $this->assertEquals(1, $summary->protectedFunctionCount());
        $this->assertEquals(2, $summary->protectedAttributeCount());
        $this->assertEquals(1, $summary->protectedTypedAttributes());
        $this->assertEquals(1, $summary->privateFunctionCount());
        $this->assertEquals(2, $summary->privateAttributeCount());
        $this->assertEquals(1, $summary->privateTypedAttributes());
        $this->assertEquals(6, $summary->functionCount());
        $this->assertEquals(6, $summary->attributeCount());
        $this->assertEquals(3, $summary->typedAttributeCount());
        $this->assertEquals(3, $summary->attributesPerClass());
        $this->assertEquals(3, $summary->functionsPerClass());
    }
}
