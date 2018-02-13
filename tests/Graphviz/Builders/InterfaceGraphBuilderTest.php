<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\InterfaceDefinition;
use PhUml\Graphviz\InheritanceEdge;
use PhUml\Graphviz\Node;
use PhUml\TestBuilders\A;

class InterfaceGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_from_a_single_interface()
    {
        $interface = new InterfaceDefinition('AnInterface');
        $graphElements = new InterfaceGraphBuilder();

        $dotElements = $graphElements->extractFrom($interface);

        $this->assertEquals([new Node($interface)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_from_an_interface_with_a_parent()
    {
        $parent = new InterfaceDefinition('ParentInterface');
        $interface = A::interface('AnInterface')->extending($parent)->build();
        $graphElements = new InterfaceGraphBuilder();

        $dotElements = $graphElements->extractFrom($interface);

        $this->assertEquals([
            new Node($interface),
            new InheritanceEdge($parent, $interface)
        ], $dotElements);
    }
}
