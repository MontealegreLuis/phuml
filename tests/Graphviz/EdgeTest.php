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

        $edge = new InheritanceEdge($parent, $child);

        $this->assertEquals($parent, $edge->fromNode());
        $this->assertEquals($child, $edge->toNode());
    }

    /** @test */
    function it_can_represent_an_implementation_relationship()
    {
        $interface = new InterfaceDefinition('AnInterface');
        $class = new ClassDefinition('AClass');

        $edge = new ImplementationEdge($interface, $class);

        $this->assertEquals($interface, $edge->fromNode());
        $this->assertEquals($class, $edge->toNode());
    }

    /** @test */
    function it_can_represent_an_association_relationship()
    {
        $reference = new ClassDefinition('AReference');
        $class = new ClassDefinition('AClass');

        $edge = new AssociationEdge($reference, $class);

        $this->assertEquals($reference, $edge->fromNode());
        $this->assertEquals($class, $edge->toNode());
    }

    /** @test */
    function it_knows_its_dot_template()
    {
        $inheritance = new InheritanceEdge(new ClassDefinition('A'), new ClassDefinition('B'));
        $implementation = new ImplementationEdge(new ClassDefinition('A'), new InterfaceDefinition('B'));
        $association = new AssociationEdge(new ClassDefinition('A'), new ClassDefinition('B'));

        $this->assertEquals('inheritance', $inheritance->dotTemplate());
        $this->assertEquals('implementation', $implementation->dotTemplate());
        $this->assertEquals('association', $association->dotTemplate());
    }
}
