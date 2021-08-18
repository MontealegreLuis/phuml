<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PHPUnit\Framework\TestCase;
use PhUml\Code\UseStatements;
use PhUml\TestBuilders\A;

final class ParsedAttributesBuilderTest extends TestCase
{
    /** @test */
    function it_extract_attributes_from_promoted_properties()
    {
        $builder = new ParsedAttributesBuilder(
            new VisibilityBuilder(),
            A::typeBuilderBuilder()->build(),
        );
        $privatePromotedProperty = new Param(new Variable('aString'), type: 'string', flags: 4);
        $protectedPromotedProperty = new Param(new Variable('aFloat'), type: 'float', flags: 2);
        $publicPromotedProperty = new Param(new Variable('aBoolean'), type: 'bool', flags: 1);
        $promotedProperties = [
            $privatePromotedProperty,
            $protectedPromotedProperty,
            $publicPromotedProperty,
        ];

        $attributes = $builder->fromPromotedProperties($promotedProperties, $this->useStatements);

        $this->assertCount(3, $attributes);
        $this->assertEquals(A::attribute('$aString')->private()->withType('string')->build(), $attributes[0]);
        $this->assertEquals(A::attribute('$aFloat')->protected()->withType('float')->build(), $attributes[1]);
        $this->assertEquals(A::attribute('$aBoolean')->public()->withType('bool')->build(), $attributes[2]);
    }

    /** @before */
    function let()
    {
        $this->useStatements = new UseStatements([]);
    }

    private UseStatements $useStatements;
}
