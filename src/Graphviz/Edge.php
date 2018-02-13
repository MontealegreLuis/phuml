<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * An edge represents 1 of 3 types of relationships between classes and interfaces
 *
 * 1. Inheritance
 * 2. Interface implementation
 * 3. Associations
 *      - Via constructor injection
 *      - Via class attributes
 */
abstract class Edge implements HasDotRepresentation
{
    /** @var HasNodeIdentifier */
    private $fromNode;

    /** @var HasNodeIdentifier */
    private $toNode;

    public function __construct(HasNodeIdentifier $nodeA, HasNodeIdentifier $nodeB)
    {
        $this->fromNode = $nodeA;
        $this->toNode = $nodeB;
    }

    public function fromNode(): HasNodeIdentifier
    {
        return $this->fromNode;
    }

    public function toNode(): HasNodeIdentifier
    {
        return $this->toNode;
    }
}
