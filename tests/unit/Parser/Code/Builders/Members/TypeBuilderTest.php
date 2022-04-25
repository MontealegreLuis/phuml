<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\CompositeType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\TestBuilders\A;

final class TypeBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_a_property_type_from_its_parsed_type()
    {
        $expectedType = TypeDeclaration::from('ClassDefinition');
        $expectedNullableType = TypeDeclaration::fromNullable('ClassDefinition');
        $typeA = $this->typeBuilder->fromPropertyType(
            new Identifier('ClassDefinition'),
            null,
            $this->useStatements
        );
        $typeB = $this->typeBuilder->fromPropertyType(
            new Identifier('ClassDefinition'),
            new Doc('/** @var OutdatedTypeFromComment */'),
            $this->useStatements,
        );
        $typeC = $this->typeBuilder->fromPropertyType(
            new Name(ClassDefinition::class),
            new Doc('/** @var OutdatedTypeFromComment */'),
            $this->useStatements,
        );
        $typeD = $this->typeBuilder->fromPropertyType(
            new NullableType('ClassDefinition'),
            new Doc('/** @var AnotherTypeFromComment */'),
            $this->useStatements,
        );

        $this->assertEquals($expectedType, $typeA);
        $this->assertEquals($expectedType, $typeB);
        $this->assertSame('ClassDefinition', (string) $typeC);
        $this->assertEquals($expectedNullableType, $typeD);
    }

    /** @test */
    function it_uses_type_in_a_property_doc_block_instead_of_generic_array_type_from_declaration()
    {
        $type = $this->typeBuilder->fromPropertyType(
            new Identifier('array'),
            new Doc('/** @var ClassDefinition[] */'),
            $this->useStatements,
        );

        $this->assertEquals(TypeDeclaration::from('ClassDefinition[]'), $type);
    }

    /** @test */
    function it_uses_type_in_method_return_dock_block_instead_generic_array_type_from_declaration()
    {
        $type = $this->typeBuilder->fromMethodReturnType(
            new Identifier('array'),
            new Doc('/** @return ClassDefinition[] */'),
            $this->useStatements,
        );

        $this->assertEquals(TypeDeclaration::from('ClassDefinition[]'), $type);
    }

    /** @test */
    function it_uses_type_in_method_parameter_dock_block_instead_generic_array_type_from_declaration()
    {
        $type = $this->typeBuilder->fromMethodParameter(
            new Identifier('array'),
            new Doc('/** @param ClassDefinition[] $definitions */'),
            '$definitions',
            $this->useStatements,
        );

        $this->assertEquals(TypeDeclaration::from('ClassDefinition[]'), $type);
    }

    /** @test */
    function it_extracts_types_from_identifiers_names()
    {
        $typeFromIdentifier = $this->typeBuilder->fromPropertyType(
            new Identifier('array'),
            null,
            $this->useStatements,
        );
        $typeFromName = $this->typeBuilder->fromPropertyType(
            new Name(['PhpParser', 'Node', 'Name']),
            null,
            $this->useStatements,
        );
        $typeFromNullableType = $this->typeBuilder->fromPropertyType(
            new NullableType(new Identifier('string')),
            null,
            $this->useStatements,
        );

        $this->assertEquals(TypeDeclaration::from('array'), $typeFromIdentifier);
        $this->assertSame('Name', (string) $typeFromName);
        $this->assertEquals(TypeDeclaration::fromNullable('string'), $typeFromNullableType);
    }

    /** @test */
    function it_extracts_types_from_parsed_type_for_intersection_and_union_types()
    {
        $unionTypeFromDocBlock = $this->typeBuilder->fromPropertyType(
            new UnionType([new Name('TypeA'), new Name('TypeB')]),
            null,
            $this->useStatements,
        );
        $intersectionTypeFromDocBlock = $this->typeBuilder->fromPropertyType(
            new IntersectionType([new Name('TypeA'), new Name('TypeB')]),
            null,
            $this->useStatements,
        );

        $this->assertEquals(
            TypeDeclaration::fromCompositeType(['TypeA', 'TypeB'], CompositeType::UNION),
            $unionTypeFromDocBlock
        );
        $this->assertEquals(
            TypeDeclaration::fromCompositeType(['TypeA', 'TypeB'], CompositeType::INTERSECTION),
            $intersectionTypeFromDocBlock
        );
    }

    /** @before */
    function let()
    {
        $this->typeBuilder = A::typeBuilderBuilder()->build();
        $this->useStatements = new UseStatements([]);
    }

    private TypeBuilder $typeBuilder;

    private UseStatements $useStatements;
}
