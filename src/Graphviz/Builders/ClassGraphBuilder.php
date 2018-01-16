<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Structure;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;

/**
 * It produces the collection of nodes and edges related to a class
 *
 * - It creates an edge with the class it extends, if any,
 * - It creates edges from the the interfaces it implements
 * - It creates a node with the class itself
 * - It, optionally, discovers associations between classes/interfaces, by inspecting both:
 *   - The class attributes
 *   - The class constructor's parameters
 */
class ClassGraphBuilder
{
    /** @var \PhUml\Graphviz\HasDotRepresentation[] */
    private $dotElements;

    /** @var AssociationsBuilder */
    private $associationsBuilder;

    /** @var bool */
    private $createAssociations;

    /** @var NodeLabelBuilder */
    private $labelBuilder;

    public function __construct(NodeLabelBuilder $labelBuilder)
    {
        $this->associationsBuilder = new NoAssociationsBuilder();
        $this->labelBuilder = $labelBuilder;
    }

    public function createAssociations(): void
    {
        $this->createAssociations = true;
    }

    /**
     * The order in which the nodes and edges are created is as follows
     *
     * 1. The edges discovered via attributes inspection
     * 2. The edges discovered via the constructor parameters
     * 3. The node representing the class itself
     * 4. The parent class, if any
     * 5. The interfaces it implements, if any
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function extractFrom(ClassDefinition $class, Structure $structure): array
    {
        $this->dotElements = [];

        if ($this->createAssociations) {
            $this->associationsBuilder = new EdgesBuilder($structure);
        }

        $this->addAssociations($this->associationsBuilder->attributesAssociationsFrom($class));
        $this->addAssociations($this->associationsBuilder->parametersAssociationsFom($class));

        $this->dotElements[] = new Node($class, $this->labelBuilder->forClass($class));

        if ($class->hasParent()) {
            $this->dotElements[] = Edge::inheritance($class->extends, $class);
        }

        foreach ($class->implements as $interface) {
            $this->dotElements[] = Edge::implementation($interface, $class);
        }

        return $this->dotElements;
    }

    /** @param Edge[] */
    private function addAssociations(array $edges): void
    {
        $this->dotElements = array_merge($this->dotElements, $edges);
    }
}
