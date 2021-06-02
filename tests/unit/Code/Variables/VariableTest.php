<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use PhUml\ContractTests\WithTypeDeclarationTests;

class VariableTest extends TestCase
{
    use WithTypeDeclarationTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = Variable::declaredWith('$parameterName', TypeDeclaration::from('string'));

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('$parameterName: string', $parameterAsString);
    }

    /** @test */
    function it_fails_getting_its_reference_name_if_it_does_not_refers_to_another_class_or_interface()
    {
        $noType = Variable::declaredWith('$noTypeForParameter');

        $this->expectException(BadMethodCallException::class);
        $noType->referenceName();
    }

    protected function memberWithoutType(): HasType
    {
        return Variable::declaredWith('$noTypeForParameter');
    }

    protected function reference(): HasType
    {
        return Variable::declaredWith('$reference', TypeDeclaration::from('AClass'));
    }

    protected function memberWithBuiltInType(): HasType
    {
        return Variable::declaredWith('$builtInAttribute', TypeDeclaration::from('float'));
    }
}
