<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

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
        $parsedClass = new Class_('AClassWithTraits', [
            'stmts' => [
                new TraitUse([
                    new Name('ATrait'),
                    new Name('AnotherTrait'),
                ]),
            ],
        ]);
        $builder = new ClassDefinitionBuilder();

        $class = $builder->build($parsedClass);

        $expectedClassWithTraits = A::class('AClassWithTraits')
            ->using(new TraitName('ATrait'), new TraitName('AnotherTrait'))
            ->build();
        $this->assertEquals($expectedClassWithTraits, $class);
    }

    /** @test */
    function it_builds_a_class_with_traits_from_multiple_use_statements()
    {
        $parsedClass = new Class_('AClassWithTraits', [
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
        $builder = new ClassDefinitionBuilder();

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
