<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plClassGraphElementsTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_for_a_simple_class()
    {
        $class = new plPhpClass('ClassName');
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(false, new plClassNameLabelBuilder());

        $dotElements = $graphElements->extractFrom($class, []);

        $this->assertEquals([new plNode($class, $label)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_a_parent()
    {
        $parent = new plPhpClass('ParentClass');
        $class = new plPhpClass('ChildClass', [], [], [], $parent);
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(false, $nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, []);

        $this->assertEquals([
            new plNode($class, $label),
            plEdge::inheritance($parent, $class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_implementing_interfaces()
    {
        $firstInterface = new plPhpInterface('FirstInterface');
        $secondInterface = new plPhpInterface('FirstInterface');
        $class = new plPhpClass('AClass', [], [], [
            $firstInterface,
            $secondInterface,
        ]);
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(false, $nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, []);

        $this->assertEquals([
            new plNode($class, $label),
            plEdge::implementation($firstInterface, $class),
            plEdge::implementation($secondInterface, $class),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_associations_in_the_constructor()
    {
        $reference = new plPhpClass('AnotherClass');
        $class = new plPhpClass('AClass', [], [
            new plPhpFunction('__construct', 'public', [
                new plPhpVariable('reference', 'AnotherClass'),
            ]),
        ]);
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(true, $nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, [$reference->name => $reference]);

        $this->assertEquals([
            plEdge::association($reference, $class),
            new plNode($class, $label),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_for_a_class_with_associations_in_the_attributes()
    {
        $firstReference = new plPhpClass('FirstClass');
        $secondReference = new plPhpClass('SecondClass');
        $class = new plPhpClass('AClass', [
                new plPhpAttribute('firstReference', 'private', 'FirstClass'),
                new plPhpAttribute('secondReference', 'private', 'SecondClass'),
            ]
        );
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(true, $nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, [
            $firstReference->name => $firstReference,
            $secondReference->name => $secondReference,
        ]);

        $this->assertEquals([
            plEdge::association($firstReference, $class),
            plEdge::association($secondReference, $class),
            new plNode($class, $label),
        ], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_of_a_class_with_all_types_of_associations()
    {
        $firstReference = new plPhpClass('FirstClass');
        $secondReference = new plPhpClass('SecondClass');
        $thirdReference = new plPhpClass('ThirdClass');
        $fourthReference = new plPhpClass('FourthClass');
        $firstInterface = new plPhpInterface('FirstInterface');
        $secondInterface = new plPhpInterface('FirstInterface');
        $parent = new plPhpClass('ParentClass');

        $class = new plPhpClass('AClass',
            [
                new plPhpAttribute('firstReference', 'private', 'FirstClass'),
                new plPhpAttribute('secondReference', 'private', 'SecondClass'),
            ],
            [
                new plPhpFunction('__construct', 'public', [
                    new plPhpVariable('thirdReference', 'ThirdClass'),
                    new plPhpVariable('fourthReference', 'FourthClass'),
                ]),
            ],
            [
                $firstInterface,
                $secondInterface,
            ],
            $parent
        );
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(true, $nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, [
            $firstReference->name => $firstReference,
            $secondReference->name => $secondReference,
            $thirdReference->name => $thirdReference,
            $fourthReference->name => $fourthReference,
        ]);

        $this->assertEquals([
            plEdge::association($firstReference, $class),
            plEdge::association($secondReference, $class),
            plEdge::association($thirdReference, $class),
            plEdge::association($fourthReference, $class),
            new plNode($class, $label),
            plEdge::inheritance($parent, $class),
            plEdge::implementation($firstInterface, $class),
            plEdge::implementation($secondInterface, $class),
        ], $dotElements);
    }

    /** @test */
    function it_ignores_associations_if_specified()
    {
        $class = new plPhpClass('AClass',
            [
                new plPhpAttribute('firstReference', 'private', 'FirstClass'),
                new plPhpAttribute('secondReference', 'private', 'SecondClass'),
            ],
            [
                new plPhpFunction('__construct', 'public', [
                    new plPhpVariable('thirdReference', 'ThirdClass'),
                    new plPhpVariable('fourthReference', 'FourthClass'),
                ]),
            ]
        );
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$class->name}</td></tr></table>>";
        $graphElements = new plClassGraphElements(false, $nodeBuilder);

        $dotElements = $graphElements->extractFrom($class, []);

        $this->assertEquals([new plNode($class, $label)], $dotElements);
    }
}
