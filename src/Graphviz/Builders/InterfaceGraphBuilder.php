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

class InterfaceGraphBuilder
{
    /** @var NodeLabelBuilder */
    private $labelBuilder;

    public function __construct(NodeLabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function extractFrom(InterfaceDefinition $interface): array
    {
        $dotElements = [];

        $dotElements[] = new Node($interface, $this->labelBuilder->labelForInterface($interface));

        if ($interface->hasParent()) {
            $dotElements[] = Edge::inheritance($interface->extends, $interface);
        }

        return $dotElements;
    }
}
