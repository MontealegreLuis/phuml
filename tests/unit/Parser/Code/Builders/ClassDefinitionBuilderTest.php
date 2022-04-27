<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Name as TraitName;
use PhUml\TestBuilders\A;

final class ClassDefinitionBuilderTest extends TestCase
{
    /** @test */
    function it_builds_a_class_with_traits()
    {
        $parsedClass = new Class_(new Identifier('AClassWithTraits'), [
            'stmts' => [
                new TraitUse([
                    new Name('ATrait'),
                    new Name('AnotherTrait'),
                ]),
            ],
        ]);
        $parsedClass->namespacedName = new Name('AClassWithTraits');

        $class = $this->builder->build($parsedClass);

        $expectedClassWithTraits = A::class('AClassWithTraits')
            ->using(new TraitName('ATrait'), new TraitName('AnotherTrait'))
            ->build();
        $this->assertEquals($expectedClassWithTraits, $class);
    }

    /** @test */
    function it_builds_a_class_with_traits_from_multiple_use_statements()
    {
        $parsedClass = new Class_(new Identifier('AClassWithTraits'), [
            'stmts' => [
                new TraitUse([
                    new Name('ATrait'),
                    new Name('AnotherTrait'),
                ]),
                new TraitUse([
                    new Name('ThirdTrait'),
                ]),
            ],
        ]);
        $parsedClass->namespacedName = new Name('AClassWithTraits');

        $class = $this->builder->build($parsedClass);

        $classWithTwoUseTraitStatements = A::class('AClassWithTraits')
            ->using(
                new TraitName('ATrait'),
                new TraitName('AnotherTrait'),
                new TraitName('ThirdTrait')
            )
            ->build();
        $this->assertEquals($classWithTwoUseTraitStatements, $class);
    }

    /** @test */
    function it_builds_an_attribute_class()
    {
        $attributeClass = new Class_(new Identifier('AnAttributeClass'), [
            'attrGroups' => [
                new AttributeGroup([
                    new Attribute(new Name('Attribute')),
                ]),
            ],
        ]);
        $attributeClass->namespacedName = new Name('AnAttributeClass');

        $class = $this->builder->build($attributeClass);

        $expectedAttributeClass = A::class('AnAttributeClass')->withIsAttribute()->build();
        $this->assertEquals($expectedAttributeClass, $class);
    }

    /** @before */
    function let()
    {
        $this->builder = new ClassDefinitionBuilder(
            A::membersBuilder()->build(),
            new UseStatementsBuilder(),
            new AttributeAnalyzer()
        );
    }

    private ClassDefinitionBuilder $builder;
}
