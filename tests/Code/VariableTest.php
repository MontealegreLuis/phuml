<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Variable;

class VariableTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedParameter = new Variable('namedParameter');

        $name = $namedParameter->name;

        $this->assertEquals('namedParameter', $name);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeParameter = new Variable('noTypeForParameter');

        $type = $noTypeParameter->type;

        $this->assertFalse($type->isPresent());
    }

    /** @test */
    function it_knows_it_has_no_type()
    {
        $noTypeParameter = new Variable('noTypeForParameter');

        $hasType = $noTypeParameter->hasType();

        $this->assertFalse($hasType);
    }

    /** @test */
    function it_knows_it_has_a_type()
    {
        $typedParameter = new Variable('typedParameter', 'string');

        $hasType = $typedParameter->hasType();

        $this->assertTrue($hasType);
    }

    /** @test */
    function it_knows_its_type()
    {
        $typedParameter = new Variable('typedParameter', 'string');

        $type = $typedParameter->type;

        $this->assertEquals('string', $type->__toString());
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = new Variable('parameterName', 'string');

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('string parameterName', $parameterAsString);
    }
}
