<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PHPUnit\Framework\TestCase;
use PhUml\ContractTests\WithTypeDeclarationTests;

class VariableTests extends TestCase
{
    use WithTypeDeclarationTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = Variable::declaredWith('$parameterName', TypeDeclaration::from('string'));

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('$parameterName: string', $parameterAsString);
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
