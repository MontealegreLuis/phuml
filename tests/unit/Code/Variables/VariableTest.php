<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PHPUnit\Framework\TestCase;
use PhUml\ContractTests\WithTypeDeclarationTests;

final class VariableTest extends TestCase
{
    use WithTypeDeclarationTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = new Variable('$parameterName', TypeDeclaration::from('string'));

        $parameterAsString = $parameter->__toString();

        $this->assertEquals('$parameterName: string', $parameterAsString);
    }

    /** @test */
    function it_has_no_references_if_its_type_does_not_refers_to_another_class_or_interface()
    {
        $noType = new Variable('$noTypeForParameter', TypeDeclaration::absent());

        $this->assertCount(0, $noType->references());
    }

    protected function memberWithoutType(): HasType
    {
        return new Variable('$noTypeForParameter', TypeDeclaration::absent());
    }

    protected function typeDeclaration(): HasType
    {
        return new Variable('$reference', TypeDeclaration::from('AClass'));
    }

    protected function memberWithBuiltInType(): HasType
    {
        return new Variable('$builtInAttribute', TypeDeclaration::from('float'));
    }
}
