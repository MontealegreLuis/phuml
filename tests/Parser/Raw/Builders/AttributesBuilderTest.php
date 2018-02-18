<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\Raw\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedVisibilityFilter;

class AttributesBuilderTest extends TestCase
{
    /** @test */
    function it_excludes_private_attributes()
    {
        $builder = new AttributesBuilder([new PrivateVisibilityFilter()]);

        $attributes = $builder->build($this->attributes);

        $this->assertCount(4, $attributes);
        $this->assertTrue($attributes[1]->isPublic());
        $this->assertTrue($attributes[2]->isPublic());
        $this->assertTrue($attributes[4]->isProtected());
        $this->assertTrue($attributes[5]->isProtected());
    }

    /** @test */
    function it_excludes_protected_attributes()
    {
        $builder = new AttributesBuilder([new ProtectedVisibilityFilter()]);

        $attributes = $builder->build($this->attributes);

        $this->assertCount(5, $attributes);
        $this->assertTrue($attributes[0]->isPrivate());
        $this->assertTrue($attributes[1]->isPublic());
        $this->assertTrue($attributes[2]->isPublic());
        $this->assertTrue($attributes[3]->isPrivate());
        $this->assertTrue($attributes[6]->isPrivate());
    }

    /** @test */
    function it_excludes_both_protected_and_private_attributes()
    {
        $builder = new AttributesBuilder([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()]);

        $attributes = $builder->build($this->attributes);

        $this->assertCount(2, $attributes);
        $this->assertTrue($attributes[1]->isPublic());
        $this->assertTrue($attributes[2]->isPublic());
    }

    /** @before */
    function createAttributes()
    {
        $this->attributes = [
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('willBeRemoved')]),
            new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('publicA')]),
            new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('publicB')]),
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('willBeRemovedToo')]),
            new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('protectedA')]),
            new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('protectedB')]),
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('toBeRemoved')]),
        ];
    }

    /** @var Property[] */
    private $attributes;
}
