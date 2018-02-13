<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * Both `ClassDefinition` and `InterfaceDefinition` objects can be nodes
 *
 * All nodes labels are HTML tables
 */
class Node implements HasDotRepresentation
{
    /** @var HasDotRepresentation */
    private $node;

    public function __construct(HasDotRepresentation $node)
    {
        $this->node = $node;
    }

    public function node(): HasDotRepresentation
    {
        return $this->node;
    }

    public function dotTemplate(): string
    {
        return 'node';
    }
}
