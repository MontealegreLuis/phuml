<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;

class EdgeTest extends TestCase
{
    /** @test */
    function it_can_represent_an_inheritance_relationship()
    {
        $parent = new ClassDefinition('ParentClass');
        $child = new ClassDefinition('ChildClass');

        $edge = Edge::inheritance($parent, $child);

        $this->assertEquals($parent, $edge->fromNode());
        $this->assertEquals($child, $edge->toNode());
    }

    /** @test */
    function it_can_represent_an_implementation_relationship()
    {
        $interface = new InterfaceDefinition('AnInterface');
        $class = new ClassDefinition('AClass');

        $edge = Edge::implementation($interface, $class);

        $this->assertEquals($interface, $edge->fromNode());
        $this->assertEquals($class, $edge->toNode());
    }

    /** @test */
    function it_can_represent_an_association_relationship()
    {
        $reference = new ClassDefinition('AReference');
        $class = new ClassDefinition('AClass');

        $edge = Edge::association($reference, $class);

        $this->assertEquals($reference, $edge->fromNode());
        $this->assertEquals($class, $edge->toNode());
    }

    /** @test */
    function it_knows_its_dot_template()
    {
        $inheritance = Edge::inheritance(new ClassDefinition('A'), new ClassDefinition('B'));
        $implementation = Edge::implementation(new ClassDefinition('A'), new InterfaceDefinition('B'));
        $association = Edge::association(new ClassDefinition('A'), new ClassDefinition('B'));

        $this->assertEquals('edge', $inheritance->dotTemplate());
        $this->assertEquals('edge', $implementation->dotTemplate());
        $this->assertEquals('edge', $association->dotTemplate());
    }
}
