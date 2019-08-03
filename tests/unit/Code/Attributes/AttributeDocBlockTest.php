<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Variables\TypeDeclaration;

class AttributeDocBlockTest extends TestCase
{
    /** @test */
    function it_extracts_a_type_declaration_from_a_var_tag()
    {
        $docBlock = AttributeDocBlock::from('/** @var TestClass $testClass */');
        $multiLineDocBlock = AttributeDocBlock::from('
        /** 
         * A description of the attribute
         *
         * @var AnotherClass $testClass 
         */');

        $this->assertEquals(TypeDeclaration::from('TestClass'), $docBlock->extractType());
        $this->assertEquals(TypeDeclaration::from('AnotherClass'), $multiLineDocBlock->extractType());
    }

    /** @test */
    function it_extracts_an_array_type_declaration()
    {
        $docBlock = AttributeDocBlock::from('/** @var TestClass[] $testClass */');
        $multiLineDocBlock = AttributeDocBlock::from('
        /** 
         * A description of the attribute
         *
         * @var AnotherClass[] $testClass 
         */');

        $this->assertEquals(TypeDeclaration::from('TestClass[]'), $docBlock->extractType());
        $this->assertEquals(TypeDeclaration::from('AnotherClass[]'), $multiLineDocBlock->extractType());
    }

    /** @test */
    function it_knows_if_it_does_not_have_a_type_declaration()
    {
        $docBlock = AttributeDocBlock::from('/** A description of the attribute */');
        $multiLineDocBlock = AttributeDocBlock::from('
        /** 
         * A description of the attribute
         */');

        $this->assertEquals(TypeDeclaration::absent(), $docBlock->extractType());
        $this->assertEquals(TypeDeclaration::absent(), $multiLineDocBlock->extractType());
    }
}
