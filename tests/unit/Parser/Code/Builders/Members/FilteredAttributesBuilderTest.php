<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\WithVisibilityAssertions;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;
use PhUml\TestBuilders\A;

final class FilteredAttributesBuilderTest extends TestCase
{
    use WithVisibilityAssertions;

    /** @test */
    function it_excludes_private_attributes()
    {
        $builder = new FilteredAttributesBuilder(
            new VisibilityBuilder(),
            new TypeBuilder(),
            new VisibilityFilters([new PrivateVisibilityFilter()])
        );

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
        $builder = new FilteredAttributesBuilder(
            new VisibilityBuilder(),
            new TypeBuilder(),
            new VisibilityFilters([new ProtectedVisibilityFilter()])
        );

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
        $builder = new FilteredAttributesBuilder(
            new VisibilityBuilder(),
            new TypeBuilder(),
            new VisibilityFilters([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()])
        );

        $attributes = $builder->build($this->attributes);

        $this->assertCount(2, $attributes);
        $this->assertPublic($attributes[1]);
        $this->assertPublic($attributes[2]);
    }

    /** @test */
    function it_extract_attributes_from_promoted_properties()
    {
        $builder = new FilteredAttributesBuilder(
            new VisibilityBuilder(),
            new TypeBuilder(),
            new VisibilityFilters()
        );
        $privatePromotedProperty = new Param(new Variable('aString'), type: 'string', flags: 4);
        $protectedPromotedProperty = new Param(new Variable('aFloat'), type: 'float', flags: 2);
        $publicPromotedProperty = new Param(new Variable('aBoolean'), type: 'bool', flags: 1);
        $regularParameter = new Param(new Variable('anArray'), type: 'array');

        $attributes = $builder->fromPromotedProperties([
            $privatePromotedProperty,
            $protectedPromotedProperty,
            $publicPromotedProperty,
            $regularParameter,
        ]);

        $this->assertCount(3, $attributes);
        $this->assertEquals(A::attribute('$aString')->private()->withType('string')->build(), $attributes[0]);
        $this->assertEquals(A::attribute('$aFloat')->protected()->withType('float')->build(), $attributes[1]);
        $this->assertEquals(A::attribute('$aBoolean')->public()->withType('bool')->build(), $attributes[2]);
    }

    /** @before */
    function let()
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
    private ?array $attributes = null;
}
