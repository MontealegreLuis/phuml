<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\WithVisibilityAssertions;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

final class AttributesBuilderTest extends TestCase
{
    use WithVisibilityAssertions;

    /** @test */
    function it_excludes_private_attributes()
    {
        $builder = new AttributesBuilder([new PrivateVisibilityFilter()]);

        $attributes = $builder->build($this->attributes);

        $this->assertCount(4, $attributes);
        $this->assertPublic($attributes[1]);
        $this->assertPublic($attributes[2]);
        $this->assertProtected($attributes[4]);
        $this->assertProtected($attributes[5]);
    }

    /** @test */
    function it_excludes_protected_attributes()
    {
        $builder = new AttributesBuilder([new ProtectedVisibilityFilter()]);

        $attributes = $builder->build($this->attributes);

        $this->assertCount(5, $attributes);
        $this->assertPrivate($attributes[0]);
        $this->assertPublic($attributes[1]);
        $this->assertPublic($attributes[2]);
        $this->assertPrivate($attributes[3]);
        $this->assertPrivate($attributes[6]);
    }

    /** @test */
    function it_excludes_both_protected_and_private_attributes()
    {
        $builder = new AttributesBuilder([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()]);

        $attributes = $builder->build($this->attributes);

        $this->assertCount(2, $attributes);
        $this->assertPublic($attributes[1]);
        $this->assertPublic($attributes[2]);
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
