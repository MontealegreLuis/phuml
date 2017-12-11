<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plPhpVariableTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedParameter = new plPhpVariable('namedParameter');

        $name = $namedParameter->name;

        $this->assertEquals('namedParameter', $name);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeParameter = new plPhpVariable('noTypeForParameter');

        $type = $noTypeParameter->type;

        $this->assertFalse($type->isPresent());
    }

    /** @test */
    function it_knows_it_has_no_type()
    {
        $noTypeParameter = new plPhpVariable('noTypeForParameter');

        $hasType = $noTypeParameter->hasType();

        $this->assertFalse($hasType);
    }

    /** @test */
    function it_knows_it_has_a_type()
    {
        $typedParameter = new plPhpVariable('typedParameter', 'string');

        $hasType = $typedParameter->hasType();

        $this->assertTrue($hasType);
    }

    /** @test */
    function it_knows_its_type()
    {
        $typedParameter = new plPhpVariable('typedParameter', 'string');

        $type = $typedParameter->type;

        $this->assertEquals('string', $type->__toString());
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = new plPhpVariable('parameterName', 'string');

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('string parameterName', $parameterAsString);
    }
}
