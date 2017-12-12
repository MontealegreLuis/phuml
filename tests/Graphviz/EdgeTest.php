<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;

class EdgeTest extends TestCase
{
    /** @test */
    function it_can_represent_inheritance_in_dot_language()
    {
        $parent = $this->prophesize(HasNodeIdentifier::class);
        $child = $this->prophesize(HasNodeIdentifier::class);
        $parent->identifier()->willReturn('ParentClass');
        $child->identifier()->willReturn('ChildClass');

        $edge = Edge::inheritance($parent->reveal(), $child->reveal());

        $this->assertEquals(
            "\"ParentClass\" -> \"ChildClass\" [dir=back arrowtail=empty style=solid]\n",
            $edge->toDotLanguage()
        );
    }

    /** @test */
    function it_can_represent_implementation_in_dot_language()
    {
        $interface = $this->prophesize(HasNodeIdentifier::class);
        $class = $this->prophesize(HasNodeIdentifier::class);
        $interface->identifier()->willReturn('AnInterface');
        $class->identifier()->willReturn('AClass');

        $edge = Edge::implementation($interface->reveal(), $class->reveal());

        $this->assertEquals(
            "\"AnInterface\" -> \"AClass\" [dir=back arrowtail=normal style=dashed]\n",
            $edge->toDotLanguage()
        );
    }

    /** @test */
    function it_can_represent_association_in_dot_language()
    {
        $reference = $this->prophesize(HasNodeIdentifier::class);
        $class = $this->prophesize(HasNodeIdentifier::class);
        $reference->identifier()->willReturn('AReference');
        $class->identifier()->willReturn('AClass');

        $edge = Edge::association($reference->reveal(), $class->reveal());

        $this->assertEquals(
            "\"AReference\" -> \"AClass\" [dir=back arrowtail=none style=dashed]\n",
            $edge->toDotLanguage()
        );
    }
}
