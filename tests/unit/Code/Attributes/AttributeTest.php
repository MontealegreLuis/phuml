<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\ContractTests\WithTypeDeclarationTests;
use PhUml\ContractTests\WithVisibilityTests;

final class AttributeTest extends TestCase
{
    use WithTypeDeclarationTests;
    use WithVisibilityTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $privateAttribute = Attribute::private('privateAttribute');
        $publicAttribute = Attribute::public('publicAttribute');
        $protectedAttribute = Attribute::protected('protectedAttribute');

        $private = $privateAttribute->__toString();
        $public = $publicAttribute->__toString();
        $protected = $protectedAttribute->__toString();

        $this->assertEquals('-privateAttribute', $private);
        $this->assertEquals('+publicAttribute', $public);
        $this->assertEquals('#protectedAttribute', $protected);
    }

    /** @test */
    function its_string_representation_includes_its_type()
    {
        $string = Attribute::public('$aString', TypeDeclaration::from('string'));
        $object = Attribute::public('$file', TypeDeclaration::from('SplFileInfo'));
        $array = Attribute::public('$array', TypeDeclaration::from('array'));
        $typedArray = Attribute::public('$directories', TypeDeclaration::from('Directory[]'));

        $this->assertEquals('+$aString: string', $string->__toString());
        $this->assertEquals('+$file: SplFileInfo', $object->__toString());
        $this->assertEquals('+$array: array', $array->__toString());
        $this->assertEquals('+$directories: Directory[]', $typedArray->__toString());
    }

    protected function memberWithoutType(): HasType
    {
        return Attribute::public('$attribute');
    }

    protected function reference(): HasType
    {
        return Attribute::public('reference', TypeDeclaration::from('AClass'));
    }

    protected function memberWithBuiltInType(): HasType
    {
        return Attribute::public('builtInAttribute', TypeDeclaration::from('float'));
    }

    protected function publicMember(): HasVisibility
    {
        return Attribute::public('$attribute');
    }

    protected function protectedMember(): HasVisibility
    {
        return Attribute::protected('$attribute');
    }

    protected function privateMember(): HasVisibility
    {
        return Attribute::private('$attribute');
    }
}
