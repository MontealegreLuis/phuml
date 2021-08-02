<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Builder\Namespace_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name as ParsedName;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Name;
use PhUml\Code\UseStatement;
use PhUml\Code\UseStatements;

final class UseStatementsBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_no_use_statements_if_class_has_no_namespace()
    {
        $class = new Class_('ClassWithoutNamespace');
        $builder = new UseStatementsBuilder();

        $uses = $builder->build($class);

        $this->assertEquals(new UseStatements([]), $uses);
    }

    /** @test */
    function it_extracts_no_use_statements_if_class_has_no_dependencies()
    {
        $namespace = new Namespace_(new ParsedName('MyNamespace'));
        $class = new Class_('ClassWithoutUseStatements', attributes: ['previous' => $namespace]);
        $builder = new UseStatementsBuilder();

        $uses = $builder->build($class);

        $this->assertEquals(new UseStatements([]), $uses);
    }

    /** @test */
    function it_extracts_multiple_use_statements()
    {
        $namespaceA = new Use_([new UseUse(new ParsedName(['AnotherNamespace', 'AnotherClass']))]);
        $namespaceB = new Use_(
            [new UseUse(new ParsedName(['ThirdNamespace', 'SecondClass']))],
            attributes: ['previous' => $namespaceA]
        );
        $class = new Class_('ClassWithoutUseStatements', attributes: ['previous' => $namespaceB]);
        $builder = new UseStatementsBuilder();

        $uses = $builder->build($class);

        $this->assertEquals(
            new UseStatements([
                new UseStatement(new Name('ThirdNamespace\SecondClass'), alias: null),
                new UseStatement(new Name('AnotherNamespace\AnotherClass'), alias: null),
            ]),
            $uses
        );
    }

    /** @test */
    function it_extracts_multiple_grouped_use_statements()
    {
        $namespaceA = new GroupUse(
            new ParsedName('AnotherNamespace'),
            [new UseUse(new ParsedName('AnotherClass')), new UseUse(new ParsedName('SecondClass'))],
        );
        $namespaceB = new Use_(
            [new UseUse(new ParsedName(['ThirdNamespace', 'ThirdClass']))],
            attributes: ['previous' => $namespaceA],
        );
        $class = new Class_('ClassWithoutUseStatements', attributes: ['previous' => $namespaceB]);
        $builder = new UseStatementsBuilder();

        $uses = $builder->build($class);

        $this->assertEquals(
            new UseStatements([
                new UseStatement(new Name('ThirdNamespace\ThirdClass'), alias: null),
                new UseStatement(new Name('AnotherNamespace\AnotherClass'), alias: null),
                new UseStatement(new Name('AnotherNamespace\SecondClass'), alias: null),
            ]),
            $uses
        );
    }

    /** @test */
    function it_extracts_use_statements_with_alias()
    {
        $namespaceA = new GroupUse(
            new ParsedName('AnotherNamespace'),
            [new UseUse(new ParsedName('AnotherClass')), new UseUse(new ParsedName('SecondClass'))],
        );
        $namespaceB = new Use_(
            [new UseUse(new ParsedName(['ThirdNamespace', 'ThirdClass']), new Identifier('MyClass'))],
            attributes: ['previous' => $namespaceA],
        );
        $class = new Class_('ClassWithoutUseStatements', attributes: ['previous' => $namespaceB]);
        $builder = new UseStatementsBuilder();

        $uses = $builder->build($class);

        $this->assertEquals(
            new UseStatements([
                new UseStatement(new Name('ThirdNamespace\ThirdClass'), new Name('MyClass')),
                new UseStatement(new Name('AnotherNamespace\AnotherClass'), alias: null),
                new UseStatement(new Name('AnotherNamespace\SecondClass'), alias: null),
            ]),
            $uses
        );
    }

    /** @test */
    function it_extracts_grouped_use_statements_with_alias()
    {
        $namespaceA = new GroupUse(
            new ParsedName('AnotherNamespace'),
            [
                new UseUse(new ParsedName('AnotherClass')),
                new UseUse(new ParsedName('SecondClass'), new Identifier('MyAlias')),
            ],
        );
        $namespaceB = new Use_(
            [new UseUse(new ParsedName(['ThirdNamespace', 'ThirdClass']), new Identifier('MyClass'))],
            attributes: ['previous' => $namespaceA],
        );
        $class = new Class_('ClassWithoutUseStatements', attributes: ['previous' => $namespaceB]);
        $builder = new UseStatementsBuilder();

        $uses = $builder->build($class);

        $this->assertEquals(
            new UseStatements([
                new UseStatement(new Name('ThirdNamespace\ThirdClass'), new Name('MyClass')),
                new UseStatement(new Name('AnotherNamespace\AnotherClass'), alias: null),
                new UseStatement(new Name('AnotherNamespace\SecondClass'), new Name('MyAlias')),
            ]),
            $uses
        );
    }
}
