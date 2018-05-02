<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;

class SubGraphTest extends TestCase
{
    /** @test */
    function it_generates_empty_label_if_no_namespace_is_provided()
    {
        $subGraph = SubGraph::for('');

        $label = $subGraph->label();

        $this->assertEmpty($label);
    }

    /** @test */
    function it_generates_no_suffix_for_cluster_id_if_no_namespaces_is_given()
    {
        $subGraph = SubGraph::for('');

        $clusterId = $subGraph->clusterId();

        $this->assertEquals('cluster_', $clusterId);
    }

    /** @test */
    function it_generates_a_label_with_the_namespace_separated_by_dots()
    {
        $subGraph = SubGraph::for(SubGraph::class);

        $label = $subGraph->label();

        $this->assertEquals('phuml.graphviz.subgraph', $label);
    }

    /** @test */
    function it_generates_a_cluster_id_suffixed_with_the_namespace_separated_by_underscores()
    {
        $subGraph = SubGraph::for(SubGraph::class);

        $clusterId = $subGraph->clusterId();

        $this->assertEquals('cluster_phuml_graphviz_subgraph', $clusterId);
    }

    /** @test */
    function it_has_access_to_its_nodes()
    {
        $subGraph = SubGraph::for(Name::from(SubGraph::class)->namespace());

        $nodeA = new Node(new ClassDefinition(Name::from('ClassA')));
        $nodeB = new Node(new ClassDefinition(Name::from('ClassB')));
        $nodeC = new Node(new ClassDefinition(Name::from('ClassC')));

        $subGraph->add($nodeA);
        $subGraph->add($nodeB);
        $subGraph->add($nodeC);

        $this->assertCount(3, $subGraph->nodes());
        $this->assertEquals($nodeA, $subGraph->nodes()[0]);
        $this->assertEquals($nodeB, $subGraph->nodes()[1]);
        $this->assertEquals($nodeC, $subGraph->nodes()[2]);
    }
}
