<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use phpDocumentor\Reflection\DocBlockFactory;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Name;
use PhUml\Code\UseStatement;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\Builders\TagTypeFactory;

final class TypeResolverTest extends TestCase
{
    /** @test */
    function it_resolves_built_in_types()
    {
        $useStatements = new UseStatements([]);

        $objectType = $this->resolver->resolveForAttribute('/** @var object */', $useStatements);
        $mixedType = $this->resolver->resolveForReturn('/** @return mixed */', $useStatements);
        $stringType = $this->resolver->resolveForParameter('/** @param string[] $test */', '$test', $useStatements);
        $boolType = $this->resolver->resolveForAttribute('/** @var bool */', $useStatements);

        $this->assertEquals('object', (string) $objectType);
        $this->assertEquals('mixed', (string) $mixedType);
        $this->assertEquals('string[]', (string) $stringType);
        $this->assertEquals('bool', (string) $boolType);
    }

    /** @test */
    function it_resolves_to_absent_type_if_doc_block_is_invalid()
    {
        $useStatements = new UseStatements([]);

        $noReturnType = $this->resolver->resolveForReturn('/** @return */', $useStatements);
        $noAttributeType = $this->resolver->resolveForAttribute('/** @var */', $useStatements);
        $noParameterType = $this->resolver->resolveForParameter('/** @param */', '$aParameter', $useStatements);

        $this->assertEquals(TypeDeclaration::absent(), $noReturnType);
        $this->assertEquals(TypeDeclaration::absent(), $noAttributeType);
        $this->assertEquals(TypeDeclaration::absent(), $noParameterType);
    }

    /** @test */
    function it_resolves_to_absent_type_if_doc_block_does_not_include_type()
    {
        $useStatements = new UseStatements([]);

        $noAttributeType = $this->resolver->resolveForAttribute('/** @var $attribute */', $useStatements);
        $noParameterType = $this->resolver->resolveForParameter(
            '/** @param $aParameter */',
            '$aParameter',
            $useStatements
        );

        $this->assertEquals(TypeDeclaration::absent(), $noAttributeType);
        $this->assertEquals(TypeDeclaration::absent(), $noParameterType);
    }

    /** @test */
    function it_resolves_nullable_scalar_types_from_return_tag()
    {
        $useStatements = new UseStatements([]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return    ?string   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::fromNullable('string'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_to_absent_return_type_if_docblock_does_not_have_return_tag()
    {
        $useStatements = new UseStatements([]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::absent(), $typeDeclaration);
    }

    /** @test */
    function it_resolves_type_with_fqn_from_return_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('PhUml\Code\Variables\TypeDeclaration'), alias: null),
        ]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return  TypeDeclaration   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::from('PhUml\Code\Variables\TypeDeclaration'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_nullable_types_from_return_tag()
    {
        $useStatements = new UseStatements([]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return  ?TypeResolver   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::fromNullable('TypeResolver'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_nullable_type_with_fqn_from_return_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('PhUml\Code\Variables\TypeDeclaration'), alias: null),
        ]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return  ?TypeDeclaration   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::fromNullable('PhUml\Code\Variables\TypeDeclaration'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_nullable_type_with_alias_from_return_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('PhUml\Code\Variables\TypeDeclaration'), new Name('Type')),
        ]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return  ?Type   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::fromNullable('PhUml\Code\Variables\TypeDeclaration'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_union_types_with_fqn_from_return_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('PhUml\Code\Variables\TypeDeclaration'), alias: null),
            new UseStatement(new Name('PhUml\Code\Variables\NullableType'), new Name('NullType')),
        ]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @return  TypeDeclaration|NullType|string   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(
            TypeDeclaration::fromUnionType([
                'PhUml\Code\Variables\TypeDeclaration',
                'PhUml\Code\Variables\NullableType',
                'string',
            ]),
            $typeDeclaration
        );
    }

    /** @test */
    function it_resolves_type_from_param_tag()
    {
        $useStatements = new UseStatements([]);
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @param ?Twig_Environment $engine This one is here because of the underscore
 * @param int[] $grades
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForParameter($comment, '$engine', $useStatements);

        $this->assertEquals(TypeDeclaration::fromNullable('Twig_Environment'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_to_absent_parameter_type_if_no_matching_param_tag_is_found()
    {
        $useStatements = new UseStatements([]);
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @param ?Twig_Environment $engine This one is here because of the underscore
 * @param int[] $grades
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForParameter($comment, '$unknown', $useStatements);

        $this->assertEquals(TypeDeclaration::absent(), $typeDeclaration);
    }

    /** @test */
    function it_resolves_nullable_type_with_fqn_from_param_tag()
    {
        $useStatements = new UseStatements([new UseStatement(new Name('Twig\Environment'), alias: null)]);
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @param ?Environment $engine This one is here because of the underscore
 * @param int[] $grades
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForParameter($comment, '$engine', $useStatements);

        $this->assertEquals(TypeDeclaration::fromNullable('Twig\Environment'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_union_types_with_fqn_from_param_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('Twig\Environment'), alias: null),
            new UseStatement(new Name('Phuml\Template\Engine'), new Name('TemplateEngine')),
        ]);
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @param Environment|TemplateEngine|null $engine This one is here because of the underscore
 * @param int[] $grades
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForParameter($comment, '$engine', $useStatements);

        $this->assertEquals(
            TypeDeclaration::fromUnionType(['Twig\Environment', 'Phuml\Template\Engine', 'null']),
            $typeDeclaration
        );
    }

    /** @test */
    function it_resolves_a_type_declaration_from_a_var_tag()
    {
        $useStatements = new UseStatements([]);
        $multiLineDocBlock = <<<'COMMENT'
        /** 
         * A description of the attribute
         *
         * @var AnotherClass $testClass 
         */'
COMMENT;

        $typeDeclaration = $this->resolver->resolveForAttribute($multiLineDocBlock, $useStatements);

        $this->assertEquals(TypeDeclaration::from('AnotherClass'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_to_absent_attribute_type_if_var_tag_is_not_present()
    {
        $useStatements = new UseStatements([]);
        $multiLineDocBlock = <<<'COMMENT'
        /** 
         * A description of the attribute
         */'
COMMENT;

        $typeDeclaration = $this->resolver->resolveForAttribute($multiLineDocBlock, $useStatements);

        $this->assertEquals(TypeDeclaration::absent(), $typeDeclaration);
    }

    /** @test */
    function it_resolves_a_type_declaration_with_fqn_from_a_var_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('PhUml\Code\AnotherClass'), new Name('Type')),
        ]);
        $multiLineDocBlock = <<<'COMMENT'
        /** 
         * A description of the attribute
         *
         * @var AnotherClass $testClass 
         */'
COMMENT;

        $typeDeclaration = $this->resolver->resolveForAttribute($multiLineDocBlock, $useStatements);

        $this->assertEquals(TypeDeclaration::from('PhUml\Code\AnotherClass'), $typeDeclaration);
    }

    /** @test */
    function it_resolves_union_types_with_fqn_from_a_var_tag()
    {
        $useStatements = new UseStatements([
            new UseStatement(new Name('Phuml\AnotherClass'), alias: null),
            new UseStatement(new Name('Phuml\Template\Engine'), new Name('OneClass')),
        ]);
        $multiLineDocBlock = <<<'COMMENT'
        /** 
         * A description of the attribute
         *
         * @var AnotherClass|OneClass $testClass 
         */'
COMMENT;

        $typeDeclaration = $this->resolver->resolveForAttribute($multiLineDocBlock, $useStatements);

        $this->assertEquals(
            TypeDeclaration::fromUnionType(['Phuml\AnotherClass', 'Phuml\Template\Engine']),
            $typeDeclaration
        );
    }

    /** @test */
    function it_resolves_union_types_from_a_return_tag()
    {
        $useStatements = new UseStatements([]);
        $methodComment = <<<'COMMENT'
/**
 * This is the short explanation of the attribute
 *
 * This is the long summary....
 *
 * @return  ClassOne|ClassTwo|null   
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForReturn($methodComment, $useStatements);

        $this->assertEquals(TypeDeclaration::fromUnionType(['ClassOne', 'ClassTwo', 'null']), $typeDeclaration);
    }

    /** @test */
    function it_resolves_union_type_from_param_tag()
    {
        $useStatements = new UseStatements([]);
        $comment = <<<'COMMENT'
/**
 * This is the short explanation of the method
 *
 * This is the long summary....
 *
 * @param string $name The name of the student
 * @param int|string|float $grade
 */
COMMENT;

        $typeDeclaration = $this->resolver->resolveForParameter($comment, '$grade', $useStatements);

        $this->assertEquals(TypeDeclaration::fromUnionType(['int', 'string', 'float']), $typeDeclaration);
    }

    /** @before */
    function let()
    {
        $this->resolver = new TypeResolver(new TagTypeFactory(DocBlockFactory::createInstance()));
    }

    private TypeResolver $resolver;
}
