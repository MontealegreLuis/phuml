<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use plPhpInterface;

class InterfaceGraphElements
{
    /** @var NodeLabelBuilder */
    private $labelBuilder;

    public function __construct(NodeLabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * @return HasDotRepresentation[]
     */
    public function extractFrom(plPhpInterface $interface): array
    {
        $dotElements = [];

        $dotElements[] = new Node($interface, $this->labelBuilder->labelForInterface($interface));

        // Create interface inheritance relation
        if ($interface->hasParent()) {
            $dotElements[] = Edge::inheritance($interface->extends, $interface);
        }

        return $dotElements;
    }
}
