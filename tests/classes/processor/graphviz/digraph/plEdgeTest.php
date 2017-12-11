<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plEdgeTest extends TestCase
{
    /** @test */
    function it_can_represent_inheritance_in_dot_language()
    {
        $parent = $this->prophesize(plHasNodeIdentifier::class);
        $child = $this->prophesize(plHasNodeIdentifier::class);
        $parent->identifier()->willReturn('ParentClass');
        $child->identifier()->willReturn('ChildClass');

        $edge = plEdge::inheritance($parent->reveal(), $child->reveal());

        $this->assertEquals(
            "\"ParentClass\" -> \"ChildClass\" [dir=back arrowtail=empty style=solid]\n",
            $edge->toDotLanguage()
        );
    }

    /** @test */
    function it_can_represent_implementation_in_dot_language()
    {
        $interface = $this->prophesize(plHasNodeIdentifier::class);
        $class = $this->prophesize(plHasNodeIdentifier::class);
        $interface->identifier()->willReturn('AnInterface');
        $class->identifier()->willReturn('AClass');

        $edge = plEdge::implementation($interface->reveal(), $class->reveal());

        $this->assertEquals(
            "\"AnInterface\" -> \"AClass\" [dir=back arrowtail=normal style=dashed]\n",
            $edge->toDotLanguage()
        );
    }

    /** @test */
    function it_can_represent_association_in_dot_language()
    {
        $reference = $this->prophesize(plHasNodeIdentifier::class);
        $class = $this->prophesize(plHasNodeIdentifier::class);
        $reference->identifier()->willReturn('AReference');
        $class->identifier()->willReturn('AClass');

        $edge = plEdge::association($reference->reveal(), $class->reveal());

        $this->assertEquals(
            "\"AReference\" -> \"AClass\" [dir=back arrowtail=none style=dashed]\n",
            $edge->toDotLanguage()
        );
    }
}
