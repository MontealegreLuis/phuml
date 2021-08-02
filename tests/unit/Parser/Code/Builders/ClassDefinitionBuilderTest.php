<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

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
        $builder = new ClassDefinitionBuilder(A::membersBuilder()->build(), new UseStatementsBuilder());

        $class = $builder->build($parsedClass);

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
        $builder = new ClassDefinitionBuilder(A::membersBuilder()->build(), new UseStatementsBuilder());

        $class = $builder->build($parsedClass);

        $classWithTwoUseTraitStatements = A::class('AClassWithTraits')
            ->using(
                new TraitName('ATrait'),
                new TraitName('AnotherTrait'),
                new TraitName('ThirdTrait')
            )
            ->build();
        $this->assertEquals($classWithTwoUseTraitStatements, $class);
    }
}
