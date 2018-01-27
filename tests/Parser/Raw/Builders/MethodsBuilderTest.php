<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;

class MethodsBuilderTest extends TestCase
{
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
            new ClassMethod('privateMethodC', ['type' => Class_::MODIFIER_PRIVATE]),
        ];
    }

    /** @test */
    function it_filters_private_methods()
    {
        $methodsBuilder = new MethodsBuilder([new PrivateMembersFilter()]);

        $rawMethods = $methodsBuilder->build($this->methods);

        $this->assertCount(4, $rawMethods);
        $this->assertEquals('public', $rawMethods[1][1]);
        $this->assertEquals('public', $rawMethods[2][1]);
        $this->assertEquals('protected', $rawMethods[4][1]);
        $this->assertEquals('protected', $rawMethods[5][1]);
    }

    /** @test */
    function it_excludes_protected_methods()
    {
        $builder = new MethodsBuilder([new ProtectedMembersFilter()]);

        $rawMethods = $builder->build($this->methods);

        $this->assertCount(5, $rawMethods);
        $this->assertEquals('private', $rawMethods[0][1]);
        $this->assertEquals('public', $rawMethods[1][1]);
        $this->assertEquals('public', $rawMethods[2][1]);
        $this->assertEquals('private', $rawMethods[3][1]);
        $this->assertEquals('private', $rawMethods[6][1]);
    }

    /** @test */
    function it_excludes_both_protected_and_public_methods()
    {
        $builder = new MethodsBuilder([new PrivateMembersFilter(), new ProtectedMembersFilter()]);

        $rawMethods = $builder->build($this->methods);

        $this->assertCount(2, $rawMethods);
        $this->assertEquals('public', $rawMethods[1][1]);
        $this->assertEquals('public', $rawMethods[2][1]);
    }

    /** @var ClassMethod[] */
    private $methods;
}
