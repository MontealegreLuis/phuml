<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

class VariableTest extends TestCase
{
    /** @test */
    function it_knows_its_name()
    {
        $namedParameter = Variable::declaredWith('$namedParameter');

        $name = $namedParameter->name;

        $this->assertEquals('$namedParameter', $name);
    }

    /** @test */
    function it_has_no_type_by_default()
    {
        $noTypeParameter = Variable::declaredWith('$noTypeForParameter');

        $type = $noTypeParameter->type;

        $this->assertFalse($type->isPresent());
    }

    /** @test */
    function it_knows_if_it_refers_to_another_class_or_interface()
    {
        $reference = Variable::declaredWith('$reference', TypeDeclaration::from('AClass'));

        $isAReference = $reference->isAReference();

        $this->assertTrue($isAReference);
    }

    /** @test */
    function it_knows_it_does_not_refers_to_another_class_or_interface()
    {
        $noType = Variable::declaredWith('$noTypeAttribute');
        $builtInType = Variable::declaredWith('$builtInAttribute', TypeDeclaration::from('float'));

        $this->assertFalse($noType->isAReference());
        $this->assertFalse($builtInType->isAReference());
    }

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = Variable::declaredWith('$parameterName', TypeDeclaration::from('string'));

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('string $parameterName', $parameterAsString);
    }
}
