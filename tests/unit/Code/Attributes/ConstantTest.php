<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\ContractTests\WithVisibilityTests;

final class ConstantTest extends TestCase
{
    use WithVisibilityTests;

    /** @test */
    function it_can_be_converted_to_string()
    {
        $publicConstant = new Constant('CONSTANT_A', TypeDeclaration::from('string'), Visibility::public());
        $protectedConstant = new Constant('CONSTANT_B', TypeDeclaration::from('string'), Visibility::protected());
        $privateConstant = new Constant('CONSTANT_C', TypeDeclaration::from('string'), Visibility::private());

        $this->assertEquals('+CONSTANT_A: string', $publicConstant->__toString());
        $this->assertEquals('#CONSTANT_B: string', $protectedConstant->__toString());
        $this->assertEquals('-CONSTANT_C: string', $privateConstant->__toString());
    }

    /** @test */
    function its_type_cannot_be_a_reference_to_a_definition_since_constants_must_be_built_in_types()
    {
        $constant = new Constant('A_CONSTANT', TypeDeclaration::from('string'), Visibility::public());

        $this->assertCount(0, $constant->references());
    }

    protected function publicMember(): HasVisibility
    {
        return new Constant('PUBLIC_CONSTANT', TypeDeclaration::from('string'), Visibility::public());
    }

    protected function protectedMember(): HasVisibility
    {
        return new Constant('PROTECTED_CONSTANT', TypeDeclaration::from('string'), Visibility::protected());
    }

    protected function privateMember(): HasVisibility
    {
        return new Constant('PRIVATE_CONSTANT', TypeDeclaration::from('string'), Visibility::private());
    }
}
