<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\ImplementationEdge;
use PhUml\Graphviz\InheritanceEdge;
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

    public function __construct(AssociationsBuilder $associationsBuilder = null)
    {
        $this->associationsBuilder = $associationsBuilder ?? new NoAssociationsBuilder();
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
    public function extractFrom(ClassDefinition $class, Codebase $codebase): array
    {
        $this->dotElements = [];

        $this->addAssociations($this->associationsBuilder->fromAttributes($class, $codebase));
        $this->addAssociations($this->associationsBuilder->fromConstructor($class, $codebase));

        $this->dotElements[] = new Node($class);

        if ($class->hasParent()) {
            $this->dotElements[] = new InheritanceEdge($class->extends(), $class);
        }

        foreach ($class->implements() as $interface) {
            $this->dotElements[] = new ImplementationEdge($interface, $class);
        }

        return $this->dotElements;
    }

    /** @param Edge[] */
    private function addAssociations(array $edges): void
    {
        $this->dotElements = array_merge($this->dotElements, $edges);
    }
}
