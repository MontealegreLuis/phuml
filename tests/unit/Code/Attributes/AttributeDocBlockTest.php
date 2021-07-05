<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Variables\TypeDeclaration;

final class AttributeDocBlockTest extends TestCase
{
    /** @test */
    function it_extracts_a_type_declaration_from_a_var_tag()
    {
        $docBlock = new AttributeDocBlock('/** @var TestClass $testClass */');
        $multiLineDocBlock = new AttributeDocBlock('
        /** 
         * A description of the attribute
         *
         * @var AnotherClass $testClass 
         */');

        $this->assertEquals(TypeDeclaration::from('TestClass'), $docBlock->attributeType());
        $this->assertEquals(TypeDeclaration::from('AnotherClass'), $multiLineDocBlock->attributeType());
    }

    /** @test */
    function it_extracts_an_array_type_declaration()
    {
        $docBlock = new AttributeDocBlock('/** @var TestClass[] $testClass */');
        $multiLineDocBlock = new AttributeDocBlock('
        /** 
         * A description of the attribute
         *
         * @var AnotherClass[] $testClass 
         */');

        $this->assertEquals(TypeDeclaration::from('TestClass[]'), $docBlock->attributeType());
        $this->assertEquals(TypeDeclaration::from('AnotherClass[]'), $multiLineDocBlock->attributeType());
    }

    /** @test */
    function it_knows_if_it_does_not_have_a_type_declaration()
    {
        $docBlock = new AttributeDocBlock('/** A description of the attribute */');
        $multiLineDocBlock = new AttributeDocBlock('
        /** 
         * A description of the attribute
         */');

        $this->assertEquals(TypeDeclaration::absent(), $docBlock->attributeType());
        $this->assertEquals(TypeDeclaration::absent(), $multiLineDocBlock->attributeType());
    }
}
