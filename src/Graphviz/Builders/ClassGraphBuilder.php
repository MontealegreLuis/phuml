<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\HasDotRepresentation;
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
final class ClassGraphBuilder
{
    /** @var HasDotRepresentation[] */
    private array $dotElements;

    private AssociationsBuilder $associationsBuilder;

    public function __construct(AssociationsBuilder $associationsBuilder = null)
    {
        $this->dotElements = [];
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
     * @return HasDotRepresentation[]
     */
    public function extractFrom(ClassDefinition $class, Codebase $codebase): array
    {
        $this->dotElements = [];

        $this->addAssociations($this->associationsBuilder->fromAttributes($class, $codebase));
        $this->addAssociations($this->associationsBuilder->fromConstructor($class, $codebase));

        $this->dotElements[] = new Node($class);

        if ($class->hasParent()) {
            $this->dotElements[] = Edge::inheritance($codebase->get($class->parent()), $class);
        }

        foreach ($class->interfaces() as $interface) {
            $this->dotElements[] = Edge::implementation($codebase->get($interface), $class);
        }

        foreach ($class->traits() as $trait) {
            $this->dotElements[] = Edge::use($codebase->get($trait), $class);
        }

        return $this->dotElements;
    }

    /** @param Edge[] $edges */
    private function addAssociations(array $edges): void
    {
        $this->dotElements = array_merge($this->dotElements, $edges);
    }
}
