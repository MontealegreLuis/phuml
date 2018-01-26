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
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;

class AttributesBuilderTest extends TestCase
{
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

    /** @test */
    function it_excludes_private_methods()
    {
        $builder = new AttributesBuilder([new PrivateMembersFilter()]);

        $rawAttributes = $builder->build($this->attributes);

        $this->assertCount(4, $rawAttributes);
        $this->assertEquals('public', $rawAttributes[1][1]);
        $this->assertEquals('public', $rawAttributes[2][1]);
        $this->assertEquals('protected', $rawAttributes[4][1]);
        $this->assertEquals('protected', $rawAttributes[5][1]);
    }

    /** @test */
    function it_excludes_protected_methods()
    {
        $builder = new AttributesBuilder([new ProtectedMembersFilter()]);

        $rawAttributes = $builder->build($this->attributes);

        $this->assertCount(5, $rawAttributes);
        $this->assertEquals('private', $rawAttributes[0][1]);
        $this->assertEquals('public', $rawAttributes[1][1]);
        $this->assertEquals('public', $rawAttributes[2][1]);
        $this->assertEquals('private', $rawAttributes[3][1]);
        $this->assertEquals('private', $rawAttributes[6][1]);
    }

    /** @test */
    function it_excludes_both_protected_and_public_methods()
    {
        $builder = new AttributesBuilder([new PrivateMembersFilter(), new ProtectedMembersFilter()]);

        $rawAttributes = $builder->build($this->attributes);

        $this->assertCount(2, $rawAttributes);
        $this->assertEquals('public', $rawAttributes[1][1]);
        $this->assertEquals('public', $rawAttributes[2][1]);
    }

    /** @var Property[] */
    private $attributes;
}
