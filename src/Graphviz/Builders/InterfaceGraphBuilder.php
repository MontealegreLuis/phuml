<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\InterfaceDefinition;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;

/**
 * It produces the collection of nodes and edges related to an interface
 *
 * It creates a node with the interface itself
 * It creates an edge using the interface it extends, if any
 */
class InterfaceGraphBuilder
{
    /** @var NodeLabelBuilder */
    private $labelBuilder;

    public function __construct(NodeLabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * The order in which the nodes and edges are created is as follows
     *
     * 1. The node representing the interface itself
     * 2. The parent interface, if any
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function extractFrom(InterfaceDefinition $interface): array
    {
        $dotElements = [];

        $dotElements[] = new Node($interface, $this->labelBuilder->forInterface($interface));

        if ($interface->hasParent()) {
            $dotElements[] = Edge::inheritance($interface->extends(), $interface);
        }

        return $dotElements;
    }
}
