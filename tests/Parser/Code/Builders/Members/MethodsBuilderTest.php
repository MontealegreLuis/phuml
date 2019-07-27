<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\WithVisibilityAssertions;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

class MethodsBuilderTest extends TestCase
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

    /** @before */
    function createMethods()
    {
        $this->methods = [
            new ClassMethod('privateMethodA', ['type' => Class_::MODIFIER_PRIVATE]),
            new ClassMethod('publicMethodA', ['type' => Class_::MODIFIER_PUBLIC]),
            new ClassMethod('publicMethodA', ['type' => Class_::MODIFIER_PUBLIC]),
            new ClassMethod('privateMethodB', ['type' => Class_::MODIFIER_PRIVATE]),
            new ClassMethod('protectedMethodA', ['type' => Class_::MODIFIER_PROTECTED]),
            new ClassMethod('protectedMethodB', ['type' => Class_::MODIFIER_PROTECTED]),
            new ClassMethod('privateMethodC', [
                'type' => Class_::MODIFIER_PRIVATE,
                'params'  => [new Param(new Variable('nullableParameter'), null, new NullableType('int'))],
            ]),
        ];
    }

    /** @var ClassMethod[] */
    private $methods;
}
