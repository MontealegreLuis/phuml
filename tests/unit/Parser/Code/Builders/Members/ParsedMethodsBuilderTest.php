<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPUnit\Framework\TestCase;
use PhUml\Code\UseStatements;
use PhUml\TestBuilders\A;

final class ParsedMethodsBuilderTest extends TestCase
{
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
        $this->builder = new ParsedMethodsBuilder(
            new ParametersBuilder($typeBuilder),
            $typeBuilder,
            new VisibilityBuilder(),
        );
    }

    private ParsedMethodsBuilder $builder;

    private UseStatements $useStatements;
}
