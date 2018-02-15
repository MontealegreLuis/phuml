<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;

/**
 * Both `ClassDefinition` and `InterfaceDefinition` objects can be nodes
 *
 * All nodes labels are HTML tables
 */
class Node implements HasDotRepresentation
{
    /** @var Definition */
    private $definition;

    public function __construct(Definition $node)
    {
        $this->definition = $node;
    }

    public function definition(): Definition
    {
        return $this->definition;
    }

    public function dotTemplate(): string
    {
        return 'node';
    }

    public function labelTemplate(): string
    {
        return $this->definition instanceof ClassDefinition ? 'class' : 'interface';
    }
}
