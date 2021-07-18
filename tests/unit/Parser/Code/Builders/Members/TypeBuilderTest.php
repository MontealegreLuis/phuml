<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Variables\TypeDeclaration;

final class TypeBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_an_attribute_type_from_its_parsed_type()
    {
        $expectedType = TypeDeclaration::from('ClassDefinition');
        $expectedNullableType = TypeDeclaration::fromNullable('ClassDefinition');
        $typeA = $this->typeBuilder->fromAttributeType(new Identifier('ClassDefinition'), null);
        $typeB = $this->typeBuilder->fromAttributeType(
            new Identifier('ClassDefinition'),
            new Doc('/** @var OutdatedTypeFromComment */')
        );
        $typeC = $this->typeBuilder->fromAttributeType(
            new Name(ClassDefinition::class),
            new Doc('/** @var OutdatedTypeFromComment */')
        );
        $typeD = $this->typeBuilder->fromAttributeType(
            new NullableType('ClassDefinition'),
            new Doc('/** @var AnotherTypeFromComment */')
        );

        $this->assertEquals($expectedType, $typeA);
        $this->assertEquals($expectedType, $typeB);
        $this->assertEquals($expectedType, $typeC);
        $this->assertEquals($expectedNullableType, $typeD);
    }

    /** @test */
    function it_uses_type_in_an_attribute_doc_block_instead_of_generic_array_type_from_declaration()
    {
        $type = $this->typeBuilder->fromAttributeType(
            new Identifier('array'),
            new Doc('/** @var ClassDefinition[] */')
        );

        $this->assertEquals(TypeDeclaration::from('ClassDefinition[]'), $type);
    }

    /** @test */
    function it_uses_type_in_method_return_dock_block_instead_generic_array_type_from_declaration()
    {
        $type = $this->typeBuilder->fromMethodReturnType(
            new Identifier('array'),
            new Doc('/** @return ClassDefinition[] */')
        );

        $this->assertEquals(TypeDeclaration::from('ClassDefinition[]'), $type);
    }

    /** @test */
    function it_uses_type_in_method_parameter_dock_block_instead_generic_array_type_from_declaration()
    {
        $type = $this->typeBuilder->fromMethodParameter(
            new Identifier('array'),
            new Doc('/** @param ClassDefinition[] $definitions */'),
            '$definitions'
        );

        $this->assertEquals(TypeDeclaration::from('ClassDefinition[]'), $type);
    }

    /** @test */
    function it_extracts_types_from_identifiers_names_and_union_types()
    {
        $typeFromIdentifier = $this->typeBuilder->fromAttributeType(new Identifier('array'), null);
        $typeFromName = $this->typeBuilder->fromAttributeType(new Name(['PhpParser', 'Node', 'Name']), null);
        $typeFromNullableType = $this->typeBuilder->fromAttributeType(
            new NullableType(new Identifier('string')),
            null
        );

        $this->assertEquals(TypeDeclaration::from('array'), $typeFromIdentifier);
        $this->assertEquals(TypeDeclaration::from('Name'), $typeFromName);
        $this->assertEquals(TypeDeclaration::fromNullable('string'), $typeFromNullableType);
    }

    /** @before */
    function let()
    {
        $this->typeBuilder = new TypeBuilder();
    }

    private TypeBuilder $typeBuilder;
}
