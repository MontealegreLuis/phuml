<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;
use PhUml\Code\UseStatements;
use PhUml\TestBuilders\A;

final class MembersBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_properties_from_promoted_properties()
    {
        $membersBuilder = A::membersBuilder()->build();
        $constructor = new ClassMethod('__construct', [
            'type' => Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param(new Variable('aString'), type: 'string', flags: 4),
                new Param(new Variable('aFloat'), type: 'float', flags: 2),
                new Param(new Variable('aBoolean'), type: 'bool', flags: 1),
                new Param(new Variable('anArray'), type: 'array'),
            ],
        ]);

        $properties = $membersBuilder->properties([], $constructor, new UseStatements([]));

        $this->assertCount(3, $properties);
        $this->assertEquals(A::property('$aString')->private()->withType('string')->build(), $properties[0]);
        $this->assertEquals(A::property('$aFloat')->protected()->withType('float')->build(), $properties[1]);
        $this->assertEquals(A::property('$aBoolean')->public()->withType('bool')->build(), $properties[2]);
    }

    /** @test */
    function it_extracts_both_regular_properties_and_promoted_properties()
    {
        $membersBuilder = A::membersBuilder()->build();
        $properties = [
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('privateProperty')]),
            new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('protectedProperty')]),
            new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('publicProperty')]),
        ];
        $constructor = new ClassMethod('__construct', [
            'type' => Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param(new Variable('aString'), type: 'string', flags: 4),
                new Param(new Variable('aFloat'), type: 'float', flags: 2),
                new Param(new Variable('aBoolean'), type: 'bool', flags: 1),
                new Param(new Variable('anArray'), type: 'array'),
            ],
        ]);

        $properties = $membersBuilder->properties($properties, $constructor, new UseStatements([]));

        $this->assertCount(6, $properties);
        $this->assertEquals(A::property('$privateProperty')->private()->build(), $properties[0]);
        $this->assertEquals(A::property('$protectedProperty')->protected()->build(), $properties[1]);
        $this->assertEquals(A::property('$publicProperty')->public()->build(), $properties[2]);
        $this->assertEquals(A::property('$aString')->private()->withType('string')->build(), $properties[3]);
        $this->assertEquals(A::property('$aFloat')->protected()->withType('float')->build(), $properties[4]);
        $this->assertEquals(A::property('$aBoolean')->public()->withType('bool')->build(), $properties[5]);
    }

    /** @test */
    function it_filters_promoted_properties_by_visibility()
    {
        $membersBuilder = A::membersBuilder()->excludePrivateMembers()->excludeProtectedMembers()->build();
        $constructor = new ClassMethod('__construct', [
            'type' => Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param(new Variable('aString'), type: 'string', flags: 4),
                new Param(new Variable('aFloat'), type: 'float', flags: 2),
                new Param(new Variable('aBoolean'), type: 'bool', flags: 1),
                new Param(new Variable('anArray'), type: 'array'),
            ],
        ]);

        $properties = $membersBuilder->properties([], $constructor, new UseStatements([]));

        $this->assertCount(1, $properties);
        $this->assertEquals(A::property('$aBoolean')->public()->withType('bool')->build(), $properties[0]);
    }
}
