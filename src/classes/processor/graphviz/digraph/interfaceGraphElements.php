<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

class plInterfaceGraphElements
{
    /** @var plNodeLabelBuilder */
    private $labelBuilder;

    public function __construct(plNodeLabelBuilder $labelBuilder)
    {
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * @return plHasDotRepresentation[]
     */
    public function extractFrom(plPhpInterface $interface): array
    {
        $dotElements = [];

        $dotElements[] = new plNode($interface, $this->labelBuilder->labelForInterface($interface));

        // Create interface inheritance relation
        if ($interface->hasParent()) {
            $dotElements[] = plEdge::inheritance($interface->extends, $interface);
        }

        return $dotElements;
    }
}
