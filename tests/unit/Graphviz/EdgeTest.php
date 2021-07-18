<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

final class EdgeTest extends TestCase
{
    /** @test */
    function it_can_represent_an_inheritance_relationship()
    {
        $parent = A::classNamed('ParentClass');
        $child = A::classNamed('ChildClass');

        $edge = Edge::inheritance($parent, $child);

        $this->assertEquals($parent, $edge->fromNode());
        $this->assertEquals($child, $edge->toNode());
    }

    /** @test */
    function it_can_represent_an_implementation_relationship()
    {
        $interface = A::interfaceNamed('AnInterface');
        $class = A::classNamed('AClass');

        $edge = Edge::implementation($interface, $class);

        $this->assertEquals($interface, $edge->fromNode());
        $this->assertEquals($class, $edge->toNode());
    }

    /** @test */
    function it_can_represent_an_association_relationship()
    {
        $reference = A::classNamed('AReference');
        $class = A::classNamed('AClass');

        $edge = Edge::association($reference, $class);

        $this->assertEquals($reference, $edge->fromNode());
        $this->assertEquals($class, $edge->toNode());
    }

    /** @test */
    function it_knows_its_dot_template()
    {
        $inheritance = Edge::inheritance(A::classNamed('A'), A::classNamed('B'));
        $implementation = Edge::implementation(A::classNamed('A'), A::interfaceNamed('B'));
        $association = Edge::association(A::classNamed('A'), A::classNamed('B'));

        $this->assertEquals('edge', $inheritance->dotTemplate());
        $this->assertEquals('edge', $implementation->dotTemplate());
        $this->assertEquals('edge', $association->dotTemplate());
    }
}
