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
            new Attribute('$attribute', 'protected'),
            new Attribute('$value', 'public', 'float'),
            new Attribute('isValid'),
        ], [
            new Method('getAttribute', 'protected'),
            new Method('privateAction', 'private')
        ]);
        $parentInterface = new InterfaceDefinition('ParentInterface', [
            new Method('dance'),
        ]);
        $interface = new InterfaceDefinition('SomeAbility', [
            new Method('fly')
        ], $parentInterface);

        $structure = new Structure();
        $structure->addClass($parentClass);
        $structure->addInterface($parentInterface);
        $structure->addInterface($interface);
        $structure->addClass(new ClassDefinition('ChildClass', [
            new Attribute('$name', 'private', 'string'),
            new Attribute('$salary', 'private'),
            new Attribute('$age', 'protected', 'int'),
        ], [
            new Method('getName'),
            new Method('getAge'),
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
