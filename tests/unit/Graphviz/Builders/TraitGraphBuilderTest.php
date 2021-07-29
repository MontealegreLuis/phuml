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

final class TraitGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extracts_the_node_for_a_trait()
    {
        $trait = A::traitNamed('ATrait');
        $builders = new TraitGraphBuilder();

        $nodes = $builders->extractFrom($trait, new Codebase());

        $this->assertCount(1, $nodes);
        $this->assertEquals([new Node($trait)], $nodes);
    }

    /** @test */
    function it_extract_the_edges_from_the_traits_it_uses()
    {
        $anotherTrait = A::traitNamed('AnotherTrait');
        $thirdTrait = A::traitNamed('ThirdTrait');
        $trait = A::trait('ATrait')
            ->using($anotherTrait->name(), $thirdTrait->name())
            ->build()
        ;
        $codebase = new Codebase();
        $codebase->add($anotherTrait);
        $codebase->add($thirdTrait);
        $builders = new TraitGraphBuilder();

        $nodes = $builders->extractFrom($trait, $codebase);

        $this->assertEquals([
            new Node($trait),
            Edge::use($anotherTrait, $trait),
            Edge::use($thirdTrait, $trait),
        ], $nodes);
    }
}
