<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Name;
use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\ContractTests\WithTypeDeclarationTests;
use PhUml\ContractTests\WithVisibilityTests;
use PhUml\TestBuilders\A;

final class AttributeTest extends TestCase
{
    use WithTypeDeclarationTests;
    use WithVisibilityTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $privateAttribute = Attribute::private(A::variable('$privateAttribute')->build());
        $publicAttribute = Attribute::public(A::variable('$publicAttribute')->build());
        $protectedAttribute = Attribute::protected(A::variable('$protectedAttribute')->build());

        $private = $privateAttribute->__toString();
        $public = $publicAttribute->__toString();
        $protected = $protectedAttribute->__toString();

        $this->assertEquals('-$privateAttribute', $private);
        $this->assertEquals('+$publicAttribute', $public);
        $this->assertEquals('#$protectedAttribute', $protected);
    }

    /** @test */
    function its_string_representation_includes_its_type()
    {
        $string = A::attribute('$aString')->public()->withType('string')->build();
        $object = A::attribute('$file')->public()->withType('SplFileInfo')->build();
        $array = A::attribute('$array')->public()->withType('array')->build();
        $typedArray = A::attribute('$directories')->public()->withType('Directory[]')->build();

        $this->assertEquals('+$aString: string', $string->__toString());
        $this->assertEquals('+$file: SplFileInfo', $object->__toString());
        $this->assertEquals('+$array: array', $array->__toString());
        $this->assertEquals('+$directories: Directory[]', $typedArray->__toString());
    }

    /** @test */
    function it_can_be_static()
    {
        $staticPublic = Attribute::staticPublic(A::variable('$staticPublic')->build());
        $staticProtected = Attribute::staticProtected(A::variable('$staticProtected')->build());
        $staticPrivate = Attribute::staticPrivate(A::variable('$staticPrivate')->build());

        $this->assertTrue($staticPublic->isStatic());
        $this->assertTrue($staticProtected->isStatic());
        $this->assertTrue($staticPrivate->isStatic());
    }

    /** @test */
    function it_knows_its_type()
    {
        $string = A::attribute('$aString')->public()->withType('string')->build();

        $this->assertEquals(TypeDeclaration::from('string'), $string->type());
    }

    /** @test */
    function it_knows_if_its_type_refers_to_another_declaration_in_the_current_codebase()
    {
        $typedArray = A::attribute('$directories')->public()->withType('Directory[]')->build();

        $this->assertTrue($typedArray->isAReference());
        $this->assertEquals(Name::from('Directory'), $typedArray->referenceName());
    }

    protected function memberWithoutType(): HasType
    {
        return A::attribute('$attribute')->public()->build();
    }

    protected function reference(): HasType
    {
        return A::attribute('$reference')->public()->withType('AClass')->build();
    }

    protected function memberWithBuiltInType(): HasType
    {
        return A::attribute('$builtInAttribute')->public()->withType('float')->build();
    }

    protected function publicMember(): HasVisibility
    {
        return A::attribute('$attribute')->public()->build();
    }

    protected function protectedMember(): HasVisibility
    {
        return Attribute::protected(A::variable('$attribute')->build());
    }

    protected function privateMember(): HasVisibility
    {
        return Attribute::private(A::variable('$attribute')->build());
    }
}
