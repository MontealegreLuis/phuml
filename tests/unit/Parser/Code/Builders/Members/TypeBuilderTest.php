<?php declare(strict_types=1);
/**
 * PHP version 7.4
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
        $typeBuilder = new TypeBuilder();
        $expectedType = TypeDeclaration::from('ClassDefinition');
        $expectedNullableType = TypeDeclaration::fromNullable('ClassDefinition');

        $typeA = $typeBuilder->fromAttributeType(new Identifier('ClassDefinition'), null);
        $typeB = $typeBuilder->fromAttributeType(new Identifier('ClassDefinition'), new Doc('/** @var OutdatedTypeFromComment */'));
        $typeC = $typeBuilder->fromAttributeType(new Name(ClassDefinition::class), new Doc('/** @var OutdatedTypeFromComment */'));
        $typeD = $typeBuilder->fromAttributeType(new NullableType('ClassDefinition'), new Doc('/** @var AnotherTypeFromComment */'));

        $this->assertEquals($expectedType, $typeA);
        $this->assertEquals($expectedType, $typeB);
        $this->assertEquals($expectedType, $typeC);
        $this->assertEquals($expectedNullableType, $typeD);
    }
}
