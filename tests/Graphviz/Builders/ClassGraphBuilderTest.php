<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Method;
use PhUml\Code\Structure;
use PhUml\Code\TypeDeclaration;
use PhUml\Code\Variable;
use PhUml\Fakes\ClassNameLabelBuilder;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;

class ClassGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_for_a_simple_class()
    {
        $class = new ClassDefinition('ClassName');
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new ClassGraphBuilder(new ClassNameLabelBuilder());

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([new Node($class, $label)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_a_parent()
    {
        $parent = new ClassDefinition('ParentClass');
        $class = new ClassDefinition('ChildClass', [], [], [], $parent);
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new ClassGraphBuilder($nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([
            new Node($class, $label),
            Edge::inheritance($parent, $class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_implementing_interfaces()
    {
        $firstInterface = new InterfaceDefinition('FirstInterface');
        $secondInterface = new InterfaceDefinition('FirstInterface');
        $class = new ClassDefinition('AClass', [], [], [
            $firstInterface,
            $secondInterface,
        ]);
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new ClassGraphBuilder($nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([
            new Node($class, $label),
            Edge::implementation($firstInterface, $class),
            Edge::implementation($secondInterface, $class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_associations_in_the_constructor()
    {
        $reference = new ClassDefinition('AnotherClass');
        $class = new ClassDefinition('AClass', [], [
            Method::public('__construct', [
                new Variable('reference', TypeDeclaration::from('AnotherClass')),
            ]),
        ]);
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $classGraphBuilder = new ClassGraphBuilder($nodeBuilder);
        $classGraphBuilder->createAssociations();
        $structure = new Structure();
        $structure->addClass($reference);

        $dotElements = $classGraphBuilder->extractFrom($class, $structure);

        $this->assertEquals([
            Edge::association($reference, $class),
            new Node($class, $label),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_associations_in_the_attributes()
    {
        $firstReference = new ClassDefinition('FirstClass');
        $secondReference = new ClassDefinition('SecondClass');
        $class = new ClassDefinition('AClass', [
                Attribute::private('firstReference', TypeDeclaration::from('FirstClass')),
                Attribute::private('secondReference', TypeDeclaration::from('SecondClass')),
            ]
        );
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $classGraphBuilder = new ClassGraphBuilder($nodeBuilder);
        $classGraphBuilder->createAssociations();
        $structure = new Structure();
        $structure->addClass($firstReference);
        $structure->addClass($secondReference);

        $dotElements = $classGraphBuilder->extractFrom($class, $structure);

        $this->assertEquals([
            Edge::association($firstReference, $class),
            Edge::association($secondReference, $class),
            new Node($class, $label),
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

        $class = new ClassDefinition('AClass',
            [
                Attribute::private('firstReference', TypeDeclaration::from('FirstClass')),
                Attribute::private('secondReference', TypeDeclaration::from('SecondClass')),
            ],
            [
                Method::public('__construct', [
                    new Variable('thirdReference', TypeDeclaration::from('ThirdClass')),
                    new Variable('fourthReference', TypeDeclaration::from('FourthClass')),
                ]),
            ],
            [
                $firstInterface,
                $secondInterface,
            ],
            $parent
        );
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $classGraphBuilder = new ClassGraphBuilder($nodeBuilder);
        $classGraphBuilder->createAssociations();
        $structure = new Structure();
        $structure->addClass($firstReference);
        $structure->addClass($secondReference);
        $structure->addClass($thirdReference);
        $structure->addClass($fourthReference);

        $dotElements = $classGraphBuilder->extractFrom($class, $structure);

        $this->assertEquals([
            Edge::association($firstReference, $class),
            Edge::association($secondReference, $class),
            Edge::association($thirdReference, $class),
            Edge::association($fourthReference, $class),
            new Node($class, $label),
            Edge::inheritance($parent, $class),
            Edge::implementation($firstInterface, $class),
            Edge::implementation($secondInterface, $class),
        ], $dotElements);
    }

    /** @test */
    function it_ignores_associations_if_specified()
    {
        $class = new ClassDefinition('AClass',
            [
                Attribute::private('firstReference', TypeDeclaration::from('FirstClass')),
                Attribute::private('secondReference', TypeDeclaration::from('SecondClass')),
            ],
            [
                Method::public('__construct', [
                    new Variable('thirdReference', TypeDeclaration::from('ThirdClass')),
                    new Variable('fourthReference', TypeDeclaration::from('FourthClass')),
                ]),
            ]
        );
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new ClassGraphBuilder($nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, new Structure());

        $this->assertEquals([new Node($class, $label)], $dotElements);
    }
}
