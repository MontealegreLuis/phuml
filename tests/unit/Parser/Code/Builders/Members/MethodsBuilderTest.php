<?php
/**
 * PHP version 7.1
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
        $methodsBuilder = new MethodsBuilder([new PrivateVisibilityFilter()]);

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
        $builder = new MethodsBuilder([new ProtectedVisibilityFilter()]);

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
        $builder = new MethodsBuilder([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()]);

        $methods = $builder->build($this->methods);

        $this->assertCount(2, $methods);
        $this->assertPublic($methods[1]);
        $this->assertPublic($methods[2]);
    }

    /** @test */
    function it_does_not_support_union_types_as_return_type()
    {
        $builder = new MethodsBuilder();
        $parsedMethods = [
            new ClassMethod('privateMethodA', [
                'type' => Class_::MODIFIER_PRIVATE,
                'returnType' => new UnionType(['int', 'float']),
                'params' => [new Param(new Variable('example'), null, new UnionType(['int', 'float']))],
            ]),
        ];

        $this->expectException(UnsupportedType::class);
        $builder->build($parsedMethods);
    }

    /** @test */
    function it_does_not_support_union_type_parameters()
    {
        $builder = new MethodsBuilder();
        $parsedMethods = [
            new ClassMethod('privateMethodA', [
                'type' => Class_::MODIFIER_PRIVATE,
                'params' => [new Param(new Variable('example'), null, new UnionType(['int', 'float']))],
            ]),
        ];

        $this->expectException(UnsupportedType::class);
        $builder->build($parsedMethods);
    }

    /** @before */
    function let()
    {
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
}