<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Node;
use PhUml\TestBuilders\A;

class TraitGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_node_for_a_trait()
    {
        $trait = A::traitNamed('ATrait');
        $builders = new TraitGraphBuilder();

        $nodes = $builders->extractFrom($trait);

        $this->assertCount(1, $nodes);
        $this->assertEquals([new Node($trait)], $nodes);
    }
}
