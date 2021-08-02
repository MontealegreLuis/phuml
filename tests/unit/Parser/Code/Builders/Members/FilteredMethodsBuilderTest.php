<?php declare(strict_types=1);
/**
 * PHP version 8.0
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
use PhUml\Code\UseStatements;
use PhUml\Fakes\WithVisibilityAssertions;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;
use PhUml\TestBuilders\A;

final class FilteredMethodsBuilderTest extends TestCase
{
    use WithVisibilityAssertions;

    /** @test */
    function it_excludes_private_methods()
    {
        $typeBuilder = A::typeBuilderBuilder()->build();
        $methodsBuilder = new FilteredMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            new VisibilityBuilder(),
            new VisibilityFilters([new PrivateVisibilityFilter()])
        );

        $methods = $methodsBuilder->build($this->methods, $this->useStatements);

        $this->assertCount(4, $methods);
        $this->assertPublic($methods[1]);
        $this->assertPublic($methods[2]);
        $this->assertProtected($methods[4]);
        $this->assertProtected($methods[5]);
    }

    /** @test */
    function it_excludes_protected_methods()
    {
        $typeBuilder = A::typeBuilderBuilder()->build();
        $builder = new FilteredMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            new VisibilityBuilder(),
            new VisibilityFilters([new ProtectedVisibilityFilter()])
        );

        $methods = $builder->build($this->methods, $this->useStatements);

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
        $typeBuilder = A::typeBuilderBuilder()->build();
        $builder = new FilteredMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            new VisibilityBuilder(),
            new VisibilityFilters([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()])
        );

        $methods = $builder->build($this->methods, $this->useStatements);

        $this->assertCount(2, $methods);
        $this->assertPublic($methods[1]);
        $this->assertPublic($methods[2]);
    }

    /** @test */
    function it_supports_union_types_as_return_type()
    {
        $parsedMethods = [
            new ClassMethod('privateMethodA', [
                'type' => Class_::MODIFIER_PRIVATE,
                'returnType' => new UnionType([new Identifier('int'), new Identifier('float')]),
            ]),
        ];

        $methods = $this->builder->build($parsedMethods, $this->useStatements);

        $this->assertCount(1, $methods);
        $this->assertEquals('-privateMethodA(): int|float', (string) $methods[0]);
    }

    /** @test */
    function it_supports_union_type_parameters()
    {
        $parsedMethods = [
            new ClassMethod('privateMethodA', [
                'type' => Class_::MODIFIER_PRIVATE,
                'params' => [new Param(
                    new Variable('example'),
                    type: new UnionType([new Identifier('int'), new Identifier('float')])
                )],
            ]),
        ];

        $methods = $this->builder->build($parsedMethods, $this->useStatements);

        $this->assertCount(1, $methods);
        $this->assertCount(1, $methods[0]->parameters());
        $this->assertEquals('$example: int|float', (string) $methods[0]->parameters()[0]);
    }

    /** @test */
    function it_builds_static_and_abstract_methods()
    {
        $parsedMethods = [
            new ClassMethod('staticMethod', [
                'type' => Class_::MODIFIER_PUBLIC | Class_::MODIFIER_STATIC,
            ]),
            new ClassMethod('abstractMethod', [
                'flags' => Class_::MODIFIER_PRIVATE | Class_::MODIFIER_ABSTRACT,
            ]),
            new ClassMethod('regularMethod', ['type' => Class_::MODIFIER_PRIVATE]),
        ];

        $methods = $this->builder->build($parsedMethods, $this->useStatements);

        $this->assertTrue($methods[0]->isStatic());
        $this->assertTrue($methods[1]->isAbstract());
        $this->assertFalse($methods[2]->isStatic());
        $this->assertFalse($methods[2]->isAbstract());
    }

    /** @before */
    function let()
    {
        $this->useStatements = new UseStatements([]);
        $typeBuilder = A::typeBuilderBuilder()->build();
        $this->builder = new FilteredMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            new VisibilityBuilder(),
            new VisibilityFilters()
        );
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
    private array $methods;

    private FilteredMethodsBuilder $builder;

    private UseStatements $useStatements;
}
