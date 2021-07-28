<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Variables\TypeDeclaration;

final class MethodDocBlockTest extends TestCase
{
    /** @test */
    function it_extracts_type_declaration_from_param_tags()
    {
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @param Twig_Environment $engine This one is here because of the underscore
 * @param int[] $grades
 */
COMMENT;

        $docBlock = new MethodDocBlock($comment);

        $this->assertEquals(TypeDeclaration::from('string'), $docBlock->typeOfParameter('$name'));
        $this->assertEquals(TypeDeclaration::from('Twig_Environment'), $docBlock->typeOfParameter('$engine'));
        $this->assertEquals(TypeDeclaration::from('int[]'), $docBlock->typeOfParameter('$grades'));
    }

    /** @test */
    function it_defaults_to_no_type_when_no_param_tags_are_available()
    {
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 */
COMMENT;

        $docBlock = new MethodDocBlock($comment);

        $this->assertEquals(TypeDeclaration::absent(), $docBlock->typeOfParameter('$name'));
    }

    /** @test */
    function it_extracts_both_parameter_type_and_return_type()
    {
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return    void   
 */
COMMENT;

        $docBlock = new MethodDocBlock($comment);

        $this->assertEquals(TypeDeclaration::from('string'), $docBlock->typeOfParameter('$name'));
        $this->assertEquals(TypeDeclaration::from('void'), $docBlock->returnType());
    }
}
