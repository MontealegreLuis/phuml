<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;
use PhUml\TestBuilders\A;

final class InterfaceGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_from_a_single_interface()
    {
        $interface = A::interfaceNamed('AnInterface');
        $graphElements = new InterfaceGraphBuilder();

        $dotElements = $graphElements->extractFrom($interface, new Codebase());

        $this->assertEquals([new Node($interface)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_from_an_interface_with_a_parent()
    {
        $parent = A::interfaceNamed('ParentInterface');
        $interface = A::interface('AnInterface')->extending($parent->name())->build();
        $codebase = new Codebase();
        $codebase->add($parent);
        $graphElements = new InterfaceGraphBuilder();

        $dotElements = $graphElements->extractFrom($interface, $codebase);

        $this->assertEquals([
            new Node($interface),
            Edge::inheritance($parent, $interface),
        ], $dotElements);
    }
}
