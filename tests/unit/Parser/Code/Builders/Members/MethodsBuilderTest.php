<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\WithVisibilityAssertions;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

final class MethodsBuilderTest extends TestCase
{
    use WithVisibilityAssertions;

    /** @test */
    function it_excludes_private_methods()
    {
        $typeBuilder = new TypeBuilder();
        $methodsBuilder = new MethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            [new PrivateVisibilityFilter()]
        );

        $methods = $methodsBuilder->build($this->methods);

        $this->assertCount(4, $methods);
        $this->assertPublic($methods[1]);
        $this->assertPublic($methods[2]);
        $this->assertProtected($methods[4]);
        $this->assertProtected($methods[5]);
    }

    /** @test */
    function it_excludes_protected_methods()
    {
        $typeBuilder = new TypeBuilder();
        $builder = new MethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            [new ProtectedVisibilityFilter()]
        );

        $methods = $builder->build($this->methods);

        $this->assertCount(5, $methods);
        $this->assertPrivate($methods[0]);
        $this->assertPublic($methods[1]);
        $this->assertPublic($methods[2]);
        $this->assertPrivate($methods[3]);
        $this->assertPrivate($methods[6]);
    }

    /** @test */
    function it_excludes_both_protected_and_private_methods()
    {
        $typeBuilder = new TypeBuilder();
        $builder = new MethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            [new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()]
        );

        $methods = $builder->build($this->methods);

        $this->assertCount(2, $methods);
        $this->assertPublic($methods[1]);
        $this->assertPublic($methods[2]);
    }

    /** @test */
    function it_does_not_support_union_types_as_return_type()
    {
        $parsedMethods = [
            new ClassMethod('privateMethodA', [
                'type' => Class_::MODIFIER_PRIVATE,
                'returnType' => new UnionType(['int', 'float']),
                'params' => [new Param(new Variable('example'), null, new UnionType(['int', 'float']))],
            ]),
        ];

        $this->expectException(UnsupportedType::class);
        $this->builder->build($parsedMethods);
    }

    /** @test */
    function it_does_not_support_union_type_parameters()
    {
        $parsedMethods = [
            new ClassMethod('privateMethodA', [
                'type' => Class_::MODIFIER_PRIVATE,
                'params' => [new Param(new Variable('example'), null, new UnionType(['int', 'float']))],
            ]),
        ];

        $this->expectException(UnsupportedType::class);
        $this->builder->build($parsedMethods);
    }

    /** @before */
    function let()
    {
        $typeBuilder = new TypeBuilder();
        $this->builder = new MethodsBuilder(new ParametersBuilder($typeBuilder), $typeBuilder);
        $this->methods = [
            new ClassMethod('privateMethodA', ['type' => Class_::MODIFIER_PRIVATE]),
            new ClassMethod('publicMethodA', [
                'type' => Class_::MODIFIER_PUBLIC,
                'returnType' => new Name('AClassName'),
            ]),
            new ClassMethod('publicMethodA', [
                'type' => Class_::MODIFIER_PUBLIC | Class_::MODIFIER_STATIC,
            ]),
            new ClassMethod('privateMethodB', [
                'flags' => Class_::MODIFIER_PRIVATE | Class_::MODIFIER_ABSTRACT,
            ]),
            new ClassMethod('protectedMethodA', [
                'type' => Class_::MODIFIER_PROTECTED,
                'returnType' => new NullableType('int'),
            ]),
            new ClassMethod('protectedMethodB', [
                'type' => Class_::MODIFIER_PROTECTED,
                'returnType' => new Identifier('Method'),
            ]),
            new ClassMethod('privateMethodC', [
                'type' => Class_::MODIFIER_PRIVATE,
                'params'  => [
                    new Param(
                        new Variable('nullableParameter'),
                        null,
                        new NullableType('int')
                    ),
                ],
                'returnType' => 'string',
            ]),
        ];
    }

    /** @var ClassMethod[] */
    private $methods;

    /** @var MethodsBuilder */
    private $builder;
}
