<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Properties;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Name;
use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\ContractTests\WithTypeDeclarationTests;
use PhUml\ContractTests\WithVisibilityTests;
use PhUml\TestBuilders\A;

final class PropertyTest extends TestCase
{
    use WithTypeDeclarationTests;
    use WithVisibilityTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $privateAttribute = new Property(A::variable('$privateAttribute')->build(), Visibility::private());
        $publicAttribute = new Property(A::variable('$publicAttribute')->build(), Visibility::public());
        $protectedAttribute = new Property(A::variable('$protectedAttribute')->build(), Visibility::protected());

        $private = $privateAttribute->__toString();
        $public = $publicAttribute->__toString();
        $protected = $protectedAttribute->__toString();

        $this->assertSame('-$privateAttribute', $private);
        $this->assertSame('+$publicAttribute', $public);
        $this->assertSame('#$protectedAttribute', $protected);
    }

    /** @test */
    function its_string_representation_includes_its_type()
    {
        $string = A::property('$aString')->public()->withType('string')->build();
        $object = A::property('$file')->public()->withType('SplFileInfo')->build();
        $array = A::property('$array')->public()->withType('array')->build();
        $typedArray = A::property('$directories')->public()->withType('Directory[]')->build();

        $this->assertSame('+$aString: string', $string->__toString());
        $this->assertSame('+$file: SplFileInfo', $object->__toString());
        $this->assertSame('+$array: array', $array->__toString());
        $this->assertSame('+$directories: Directory[]', $typedArray->__toString());
    }

    /** @test */
    function it_can_be_static()
    {
        $staticPublic = new Property(A::variable('$staticPublic')->build(), Visibility::public(), true);
        $staticProtected = new Property(A::variable('$staticProtected')->build(), Visibility::protected(), true);
        $staticPrivate = new Property(A::variable('$staticPrivate')->build(), Visibility::private(), true);

        $this->assertTrue($staticPublic->isStatic());
        $this->assertTrue($staticProtected->isStatic());
        $this->assertTrue($staticPrivate->isStatic());
    }

    /** @test */
    function it_knows_its_type()
    {
        $string = A::property('$aString')->public()->withType('string')->build();

        $this->assertEquals(TypeDeclaration::from('string'), $string->type());
    }

    /** @test */
    function it_knows_if_its_type_refers_to_another_declaration_in_the_current_codebase()
    {
        $typedArray = A::property('$directories')->public()->withType('Directory[]')->build();

        $this->assertCount(1, $typedArray->references());
        $this->assertEquals(new Name('Directory'), $typedArray->references()[0]);
    }

    protected function memberWithoutType(): HasType
    {
        return A::property('$property')->public()->build();
    }

    protected function typeDeclaration(): HasType
    {
        return A::property('$reference')->public()->withType('AClass')->build();
    }

    protected function memberWithBuiltInType(): HasType
    {
        return A::property('$builtInProperty')->public()->withType('float')->build();
    }

    protected function publicMember(): HasVisibility
    {
        return A::property('$property')->public()->build();
    }

    protected function protectedMember(): HasVisibility
    {
        return new Property(A::variable('$property')->build(), Visibility::protected());
    }

    protected function privateMember(): HasVisibility
    {
        return new Property(A::variable('$property')->build(), Visibility::private());
    }
}
