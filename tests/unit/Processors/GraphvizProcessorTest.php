<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Code\Name;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\TestBuilders\A;

final class GraphvizProcessorTest extends TestCase
{
    use WithDotLanguageAssertions;

    /** @test */
    function it_has_a_name()
    {
        $processor = A::graphvizProcessor()->build();

        $name = $processor->name();

        $this->assertSame('Graphviz', $name);
    }

    /** @test */
    function it_turns_a_codebase_into_dot_language()
    {
        $processor = A::graphvizProcessor()->withAssociations()->build();
        $parentInterface = A::interfaceNamed('ParentInterface');
        $interface = A::interface('ImplementedInterface')
            ->extending($parentInterface->name())
            ->build();
        $parentClass = A::classNamed('ParentClass');
        $reference = A::classNamed('ReferencedClass');
        $trait = A::trait('ATrait')
            ->withAProtectedProperty('$variable')
            ->withAPublicMethod('doSomething')
            ->build();
        $class = A::class('MyClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$reference')->withType('ReferencedClass')->build()
            )
            ->implementing($interface->name())
            ->extending($parentClass->name())
            ->using($trait->name())
            ->build();
        $enum = A::enum('AnEnum')
            ->implementing(new Name('ImplementedInterface'))
            ->using(new Name('ATrait'))
            ->build();
        $codebase = new Codebase();
        $codebase->add($parentClass);
        $codebase->add($reference);
        $codebase->add($parentInterface);
        $codebase->add($interface);
        $codebase->add($class);
        $codebase->add($trait);
        $codebase->add($enum);

        $dotLanguage = $processor->process($codebase);

        $this->assertNode($parentClass, $dotLanguage->value());
        $this->assertNode($reference, $dotLanguage->value());
        $this->assertNode($class, $dotLanguage->value());
        $this->assertNode($enum, $dotLanguage->value());
        $this->assertInheritance($class, $parentClass, $dotLanguage->value());
        $this->assertAssociation($reference, $class, $dotLanguage->value());
        $this->assertImplementation($class, $interface, $dotLanguage->value());
        $this->assertImplementation($enum, $interface, $dotLanguage->value());
        $this->assertNode($parentInterface, $dotLanguage->value());
        $this->assertNode($interface, $dotLanguage->value());
        $this->assertInheritance($interface, $parentInterface, $dotLanguage->value());
        $this->assertNode($trait, $dotLanguage->value());
        $this->assertUseTrait($class, $trait, $dotLanguage->value());
        $this->assertUseTrait($enum, $trait, $dotLanguage->value());
    }
}
