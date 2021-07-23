<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Const_;
use PhpParser\Node\NullableType;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\Visibility;

final class VisibilityBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_visibility_from_attributes()
    {
        $public = new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('name')]);
        $private = new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('address')]);
        $protected = new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('age')]);
        $builder = new VisibilityBuilder();

        $publicVisibility = $builder->build($public);
        $protectedVisibility = $builder->build($protected);
        $privateVisibility = $builder->build($private);

        $this->assertEquals(Visibility::public(), $publicVisibility);
        $this->assertEquals(Visibility::protected(), $protectedVisibility);
        $this->assertEquals(Visibility::private(), $privateVisibility);
    }

    /** @test */
    function it_extracts_visibility_from_methods()
    {
        $public = new ClassMethod('publicMethodA', [
            'type' => Class_::MODIFIER_PUBLIC | Class_::MODIFIER_STATIC,
        ]);
        $private = new ClassMethod('privateMethodB', [
            'flags' => Class_::MODIFIER_PRIVATE | Class_::MODIFIER_ABSTRACT,
        ]);
        $protected = new ClassMethod('protectedMethodA', [
            'type' => Class_::MODIFIER_PROTECTED,
            'returnType' => new NullableType('int'),
        ]);
        $builder = new VisibilityBuilder();

        $publicVisibility = $builder->build($public);
        $protectedVisibility = $builder->build($protected);
        $privateVisibility = $builder->build($private);

        $this->assertEquals(Visibility::public(), $publicVisibility);
        $this->assertEquals(Visibility::protected(), $protectedVisibility);
        $this->assertEquals(Visibility::private(), $privateVisibility);
    }

    /** @test */
    function it_extracts_visibility_from_constants()
    {
        $public = new ClassConst([new Const_('INTEGER', new LNumber(1))], Class_::MODIFIER_PUBLIC);
        $private = new ClassConst([new Const_('INTEGER', new LNumber(1))], Class_::MODIFIER_PRIVATE);
        $protected = new ClassConst([new Const_('INTEGER', new LNumber(1))], Class_::MODIFIER_PROTECTED);
        $builder = new VisibilityBuilder();

        $publicVisibility = $builder->build($public);
        $protectedVisibility = $builder->build($protected);
        $privateVisibility = $builder->build($private);

        $this->assertEquals(Visibility::public(), $publicVisibility);
        $this->assertEquals(Visibility::protected(), $protectedVisibility);
        $this->assertEquals(Visibility::private(), $privateVisibility);
    }

    /** @test */
    function it_extracts_visibility_from_parsed_flags()
    {
        $builder = new VisibilityBuilder();

        $private = $builder->fromFlags(4);
        $protected = $builder->fromFlags(2);
        $public = $builder->fromFlags(1);

        $this->assertEquals(Visibility::private(), $private);
        $this->assertEquals(Visibility::protected(), $protected);
        $this->assertEquals(Visibility::public(), $public);
        $this->expectException(UnknownVisibilityFlag::class);
        $builder->fromFlags(0);
    }
}
