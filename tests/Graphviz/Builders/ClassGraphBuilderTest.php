<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Structure;
use PhUml\Graphviz\AssociationEdge;
use PhUml\Graphviz\ImplementationEdge;
use PhUml\Graphviz\InheritanceEdge;
use PhUml\Graphviz\Node;
use PhUml\TestBuilders\A;

class ClassGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_for_a_simple_class()
    {
        $class = new ClassDefinition('ClassName');
        $graphElements = new ClassGraphBuilder();

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([new Node($class)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_a_parent()
    {
        $parent = new ClassDefinition('ParentClass');
        $class = A::class('ChildClass')->extending($parent)->build();
        $graphElements = new ClassGraphBuilder();

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([
            new Node($class),
            new InheritanceEdge($parent, $class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_implementing_interfaces()
    {
        $firstInterface = new InterfaceDefinition('FirstInterface');
        $secondInterface = new InterfaceDefinition('FirstInterface');
        $class = A::class('AClass')
            ->implementing($firstInterface, $secondInterface)
            ->build();
        $graphElements = new ClassGraphBuilder();

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([
            new Node($class),
            new ImplementationEdge($firstInterface, $class),
            new ImplementationEdge($secondInterface, $class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_associations_in_the_constructor()
    {
        $reference = new ClassDefinition('AnotherClass');
        $class = A::class('AClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$reference')->withType($reference->name())->build()
            )
            ->build();
        $classGraphBuilder = new ClassGraphBuilder(new EdgesBuilder());
        $structure = new Structure();
        $structure->addClass($reference);

        $dotElements = $classGraphBuilder->extractFrom($class, $structure);

        $this->assertEquals([
            new AssociationEdge($reference, $class),
            new Node($class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_associations_in_the_attributes()
    {
        $firstReference = new ClassDefinition('FirstClass');
        $secondReference = new ClassDefinition('SecondClass');
        $class = A::class('AClass')
            ->withAPrivateAttribute('$firstReference', $firstReference->name())
            ->withAPrivateAttribute('$secondReference', $secondReference->name())
            ->build();
        $classGraphBuilder = new ClassGraphBuilder(new EdgesBuilder());
        $structure = new Structure();
        $structure->addClass($firstReference);
        $structure->addClass($secondReference);

        $dotElements = $classGraphBuilder->extractFrom($class, $structure);

        $this->assertEquals([
            new AssociationEdge($firstReference, $class),
            new AssociationEdge($secondReference, $class),
            new Node($class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_of_a_class_with_all_types_of_associations()
    {
        $firstReference = new ClassDefinition('FirstClass');
        $secondReference = new ClassDefinition('SecondClass');
        $thirdReference = new ClassDefinition('ThirdClass');
        $fourthReference = new ClassDefinition('FourthClass');
        $firstInterface = new InterfaceDefinition('FirstInterface');
        $secondInterface = new InterfaceDefinition('FirstInterface');
        $parent = new ClassDefinition('ParentClass');

        $class = A::class('AClass')
            ->withAPrivateAttribute('$firstReference', $firstReference->name())
            ->withAPrivateAttribute('$secondReference', $secondReference->name())
            ->withAPublicMethod(
                '__construct',
                A::parameter('$thirdReference')->withType($thirdReference->name())->build(),
                A::parameter('$fourthReference')->withType($fourthReference->name())->build()
            )
            ->implementing($firstInterface, $secondInterface)
            ->extending($parent)
            ->build();
        $classGraphBuilder = new ClassGraphBuilder(new EdgesBuilder());
        $structure = new Structure();
        $structure->addClass($firstReference);
        $structure->addClass($secondReference);
        $structure->addClass($thirdReference);
        $structure->addClass($fourthReference);

        $dotElements = $classGraphBuilder->extractFrom($class, $structure);

        $this->assertEquals([
            new AssociationEdge($firstReference, $class),
            new AssociationEdge($secondReference, $class),
            new AssociationEdge($thirdReference, $class),
            new AssociationEdge($fourthReference, $class),
            new Node($class),
            new InheritanceEdge($parent, $class),
            new ImplementationEdge($firstInterface, $class),
            new ImplementationEdge($secondInterface, $class),
        ], $dotElements);
    }

    /** @test */
    function it_ignores_associations_if_specified()
    {
        $class = A::class('AClass')
            ->withAPrivateAttribute('$firstReference', 'FirstClass')
            ->withAPrivateAttribute('$secondReference', 'SecondClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$thirdReference')->withType('ThirdClass')->build(),
                A::parameter('$fourthReference')->withType('FourthClass')->build()
            )
            ->build();
        $graphElements = new ClassGraphBuilder();

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([new Node($class)], $dotElements);
    }
}
