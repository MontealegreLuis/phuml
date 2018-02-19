<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Fakes\WithNumericIds;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\TestBuilders\A;

class GraphvizProcessorTest extends TestCase
{
    use WithNumericIds, WithDotLanguageAssertions;

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

        $parentInterface = new NumericIdInterface('ParentInterface');
        $interface = A::interface('ImplementedInterface')->extending($parentInterface)->buildWithNumericId();
        $parentClass = new NumericIdClass('ParentClass');
        $reference = new NumericIdClass('ReferencedClass');
        $class = A::class('MyClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$reference')->withType('ReferencedClass')->build()
            )
            ->implementing($interface)
            ->extending($parentClass->name())
            ->buildWithNumericId()
        ;

        $codebase = new Codebase();
        $codebase->add($parentClass);
        $codebase->add($reference);
        $codebase->add($parentInterface);
        $codebase->add($interface);
        $codebase->add($class);

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
    }
}
