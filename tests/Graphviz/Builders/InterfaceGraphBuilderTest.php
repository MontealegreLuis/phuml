<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\InterfaceDefinition;
use PhUml\Fakes\ClassNameLabelBuilder;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;
use PhUml\TestBuilders\A;

class InterfaceGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_from_a_single_interface()
    {
        $interface = new InterfaceDefinition('AnInterface');
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$interface->name}</td></tr></table>>";
        $graphElements = new InterfaceGraphBuilder($nodeBuilder);

        $dotElements = $graphElements->extractFrom($interface);

        $this->assertEquals([new Node($interface, $label)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_from_an_interface_with_a_parent()
    {
        $parent = new InterfaceDefinition('ParentInterface');
        $interface = A::interface('AnInterface')->extending($parent)->build();
        $nodeBuilder = new ClassNameLabelBuilder();
        $label = "<<table><tr><td>{$interface->name}</td></tr></table>>";
        $graphElements = new InterfaceGraphBuilder($nodeBuilder);

        $dotElements = $graphElements->extractFrom($interface);

        $this->assertEquals([
            new Node($interface, $label),
            Edge::inheritance($parent, $interface)
        ], $dotElements);
    }
}
