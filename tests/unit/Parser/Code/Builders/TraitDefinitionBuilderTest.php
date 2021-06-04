<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Attributes\StaticAttribute;
use PhUml\Code\Methods\AbstractMethod;
use PhUml\Code\Methods\StaticMethod;
use PhUml\Code\Name as TraitName;
use PhUml\TestBuilders\A;

final class TraitDefinitionBuilderTest extends TestCase
{
    /** @test */
    function it_builds_a_trait_definition()
    {
        $parsedTrait = new Trait_('ATrait');

        $trait = $this->builder->build($parsedTrait);

        $this->assertEquals(A::traitNamed('ATrait'), $trait);
    }

    /** @test */
    function it_builds_a_trait_with_methods()
    {
        $parsedTrait = new Trait_('ATrait', [
            'stmts' => [
                new ClassMethod('privateMethod', ['type' => Class_::MODIFIER_PRIVATE]),
                new ClassMethod('protectedMethod', ['type' => Class_::MODIFIER_PROTECTED]),
                new ClassMethod('staticMethod', ['type' => Class_::MODIFIER_STATIC]),
                new ClassMethod('abstractMethod', ['type' => Class_::MODIFIER_ABSTRACT]),
            ],
        ]);

        $trait = $this->builder->build($parsedTrait);

        $traitWithMultipleTypesOfMethods = A::trait('ATrait')
            ->withAPrivateMethod('privateMethod')
            ->withAProtectedMethod('protectedMethod')
            ->withAMethod(StaticMethod::public('staticMethod'))
            ->withAMethod(AbstractMethod::public('abstractMethod'))
            ->build();
        $this->assertEquals($traitWithMultipleTypesOfMethods, $trait);
    }

    /** @test */
    function it_builds_a_trait_with_attributes()
    {
        $parsedTrait = new Trait_('ATrait', [
            'stmts' => [
                new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('privateAttribute')]),
                new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('protectedAttribute')]),
                new Property(Class_::MODIFIER_STATIC, [new PropertyProperty('staticAttribute')]),
            ],
        ]);

        $trait = $this->builder->build($parsedTrait);

        $traitWithMultipleTypesOfAttributes = A::trait('ATrait')
            ->withAPrivateAttribute('$privateAttribute')
            ->withAProtectedAttribute('$protectedAttribute')
            ->withAnAttribute(StaticAttribute::public('$staticAttribute'))
            ->build();
        $this->assertEquals($traitWithMultipleTypesOfAttributes, $trait);
    }

    /** @test */
    function it_builds_a_traits_using_multiple_traits()
    {
        $parsedTrait = new Trait_('ATraitWithTraits', [
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

        $trait = $this->builder->build($parsedTrait);

        $traitUsingOtherTraits = A::trait('ATraitWithTraits')
            ->using(
                TraitName::from('ATrait'),
                TraitName::from('AnotherTrait'),
                TraitName::from('ThirdTrait')
            )
            ->build();
        $this->assertEquals($traitUsingOtherTraits, $trait);
    }

    /** @before */
    function createBuilder()
    {
        $this->builder = new TraitDefinitionBuilder();
    }

    /** @var TraitDefinitionBuilder */
    private $builder;
}
