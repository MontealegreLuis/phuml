<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class SummaryTest extends TestCase
{
    /** @test */
    function it_generates_a_summary_from_a_code_structure()
    {
        $parentClass = new ClassDefinition('ParentClass', [
            Attribute::protected('$attribute'),
            Attribute::public('$value', TypeDeclaration::from('float')),
            Attribute::public('isValid'),
        ], [
            Method::protected('getAttribute'),
            Method::private('privateAction'),
        ]);
        $parentInterface = new InterfaceDefinition('ParentInterface', [
            Method::public('dance'),
        ]);
        $interface = new InterfaceDefinition('SomeAbility', [
            Method::public('fly')
        ], $parentInterface);

        $structure = new Structure();
        $structure->addClass($parentClass);
        $structure->addInterface($parentInterface);
        $structure->addInterface($interface);
        $structure->addClass(new ClassDefinition('ChildClass', [
            Attribute::private('$name', TypeDeclaration::from('string')),
            Attribute::private('$salary'),
            Attribute::protected('$age', TypeDeclaration::from('int')),
        ], [
            Method::public('getName'),
            Method::public('getAge'),
        ], [$interface], $parentClass));
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
