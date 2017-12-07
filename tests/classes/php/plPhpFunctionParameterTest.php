<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plPhpFunctionParameterTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedParameter = new plPhpFunctionParameter('namedParameter');

        $name = $namedParameter->name;

        $this->assertEquals('namedParameter', $name);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeParameter = new plPhpFunctionParameter('noTypeForParameter');

        $type = $noTypeParameter->type;

        $this->assertNull($type);
    }

    /** @test */
    function it_knows_it_has_no_type()
    {
        $noTypeParameter = new plPhpFunctionParameter('noTypeForParameter');

        $hasType = $noTypeParameter->hasType();

        $this->assertFalse($hasType);
    }

    /** @test */
    function it_knows_it_has_a_type()
    {
        $typedParameter = new plPhpFunctionParameter('typedParameter', 'string');

        $hasType = $typedParameter->hasType();

        $this->assertTrue($hasType);
    }

    /** @test */
    function it_knows_its_type()
    {
        $typedParameter = new plPhpFunctionParameter('typedParameter', 'string');

        $type = $typedParameter->type;

        $this->assertEquals('string', $type);
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = new plPhpFunctionParameter('parameterName');

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('parameterName', $parameterAsString);
    }
}
