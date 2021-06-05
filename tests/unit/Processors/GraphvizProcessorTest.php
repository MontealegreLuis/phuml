<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Fakes\WithNumericIds;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\TestBuilders\A;

final class GraphvizProcessorTest extends TestCase
{
    use WithNumericIds;
    use WithDotLanguageAssertions;

    /** @test */
    function it_has_a_name()
    {
        $processor = new GraphvizProcessor();

        $name = $processor->name();

        $this->assertEquals('Graphviz', $name);
    }

    /** @test */
    function it_turns_a_code_structure_into_dot_language()
    {
        $processor = new GraphvizProcessor(new ClassGraphBuilder(new EdgesBuilder()));

        $parentInterface = A::numericIdInterfaceNamed('ParentInterface');
        $interface = A::interface('ImplementedInterface')
            ->extending($parentInterface->name())
            ->buildWithNumericId();
        $parentClass = A::numericIdClassNamed('ParentClass');
        $reference = A::numericIdClassNamed('ReferencedClass');
        $trait = A::trait('ATrait')
            ->withAProtectedAttribute('$variable')
            ->withAPublicMethod('doSomething')
            ->buildWithNumericId()
        ;
        $class = A::class('MyClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$reference')->withType('ReferencedClass')->build()
            )
            ->implementing($interface->name())
            ->extending($parentClass->name())
            ->using($trait->name())
            ->buildWithNumericId()
        ;

        $codebase = new Codebase();
        $codebase->add($parentClass);
        $codebase->add($reference);
        $codebase->add($parentInterface);
        $codebase->add($interface);
        $codebase->add($class);
        $codebase->add($trait);

        $dotLanguage = $processor->process($codebase);

        $this->assertNode($parentClass, $dotLanguage);
        $this->assertNode($reference, $dotLanguage);
        $this->assertNode($class, $dotLanguage);
        $this->assertInheritance($class, $parentClass, $dotLanguage);
        $this->assertAssociation($reference, $class, $dotLanguage);
        $this->assertImplementation($class, $interface, $dotLanguage);
        $this->assertNode($parentInterface, $dotLanguage);
        $this->assertNode($interface, $dotLanguage);
        $this->assertInheritance($interface, $parentInterface, $dotLanguage);
        $this->assertNode($trait, $dotLanguage);
        $this->assertUseTrait($class, $trait, $dotLanguage);
    }
}
