<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Methods\Method;
use PhUml\Code\Structure;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\WithNumericIds;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;

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
        $interface = new NumericIdInterface('ImplementedInterface', [], [], $parentInterface);
        $parentClass = new NumericIdClass('ParentClass');
        $reference = new NumericIdClass('ReferencedClass');
        $class = new NumericIdClass('MyClass', [], [], [
            Method::public ('__construct', [
                Variable::declaredWith('$reference', TypeDeclaration::from('ReferencedClass')),
            ])
        ], [$interface], $parentClass);

        $structure = new Structure();
        $structure->addClass($parentClass);
        $structure->addClass($reference);
        $structure->addInterface($parentInterface);
        $structure->addInterface($interface);
        $structure->addClass($class);

        $dotLanguage = $processor->process($structure);

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
