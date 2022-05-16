<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\TraitUse;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Name as DefinitionName;
use PhUml\TestBuilders\A;

final class EnumDefinitionBuilderTest extends TestCase
{
    /** @test */
    function it_builds_an_enum_with_traits()
    {
        $parsedEnum = new Enum_(new Identifier('AnEnumWithTraits'), [
            'stmts' => [
                new TraitUse([
                    new Name('ATrait'),
                    new Name('AnotherTrait'),
                ]),
            ],
        ]);
        $parsedEnum->namespacedName = new Name('AnEnumWithTraits');

        $enum = $this->builder->build($parsedEnum);

        $enumDefinition = A::enum('AnEnumWithTraits')
            ->using(new DefinitionName('ATrait'), new DefinitionName('AnotherTrait'))
            ->build();
        $this->assertEquals($enumDefinition, $enum);
    }

    /** @test */
    function it_builds_an_enum_implementing_interfaces()
    {
        $parsedEnum = new Enum_(new Identifier('AnEnumImplementingInterfaces'), [
            'implements' => [
                new Name('AnInterface'),
                new Name('AnotherInterface'),
            ],
        ]);
        $parsedEnum->namespacedName = new Name('AnEnumImplementingInterfaces');

        $enum = $this->builder->build($parsedEnum);

        $enumDefinition = A::enum('AnEnumImplementingInterfaces')
            ->implementing(new DefinitionName('AnInterface'), new DefinitionName('AnotherInterface'))
            ->build();
        $this->assertEquals($enumDefinition, $enum);
    }

    /** @test */
    function it_builds_an_enum_with_methods()
    {
        $parsedEnum = new Enum_('AnEnum', [
            'stmts' => [
                new ClassMethod('privateMethod', ['type' => Class_::MODIFIER_PRIVATE]),
                new ClassMethod('protectedMethod', ['type' => Class_::MODIFIER_PROTECTED]),
                new ClassMethod('staticMethod', ['type' => Class_::MODIFIER_STATIC]),
                new ClassMethod('publicMethod', ['type' => Class_::MODIFIER_PUBLIC]),
            ],
        ]);
        $parsedEnum->namespacedName = 'AnEnum';

        $enum = $this->builder->build($parsedEnum);

        $enumWithMultipleTypesOfMethods = A::enum('AnEnum')
            ->withAPrivateMethod('privateMethod')
            ->withAProtectedMethod('protectedMethod')
            ->withAMethod(A::method('staticMethod')->public()->static()->build())
            ->withAMethod(A::method('publicMethod')->public()->build())
            ->build();
        $this->assertEquals($enumWithMultipleTypesOfMethods, $enum);
    }

    /** @test */
    function it_builds_an_enum_with_constants()
    {
        $parsedEnum = new Enum_('AnEnum', [
            'stmts' => [
                new ClassConst([new Const_('INTEGER', new LNumber(1))]),
                new ClassConst([new Const_('FLOAT', new DNumber(1.5))], Class_::MODIFIER_PRIVATE),
                new ClassConst([new Const_('STRING', new String_('test'))], Class_::MODIFIER_PROTECTED),
                new ClassConst([new Const_('IS_TRUE', new ConstFetch(new Name(['false'])))]),
            ],
        ]);
        $parsedEnum->namespacedName = 'AnEnum';

        $enum = $this->builder->build($parsedEnum);

        $enumWithMultipleTypesOfMethods = A::enum('AnEnum')
            ->withConstants(
                A::constant('INTEGER')->public()->withType('int')->build(),
                A::constant('FLOAT')->private()->withType('float')->build(),
                A::constant('STRING')->protected()->withType('string')->build(),
                A::constant('IS_TRUE')->public()->withType('bool')->build(),
            )
            ->build();
        $this->assertEquals($enumWithMultipleTypesOfMethods, $enum);
    }

    /** @test */
    function it_builds_an_enum_with_cases()
    {
        $parsedEnum = new Enum_('Visibility', [
            'stmts' => [
                new EnumCase('PRIVATE'),
                new EnumCase('PROTECTED'),
                new EnumCase('PUBLIC'),
            ],
        ]);
        $parsedEnum->namespacedName = 'AnEnum';

        $enum = $this->builder->build($parsedEnum);

        $enumWithMultipleTypesOfMethods = A::enum('AnEnum')
            ->withCases('PRIVATE', 'PROTECTED', 'PUBLIC')
            ->build();
        $this->assertEquals($enumWithMultipleTypesOfMethods, $enum);
    }

    /** @before */
    function let()
    {
        $this->builder = new EnumDefinitionBuilder(A::membersBuilder()->build(), new UseStatementsBuilder());
    }

    private EnumDefinitionBuilder $builder;
}
