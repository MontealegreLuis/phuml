<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;

/**
 * `ClassDefinition`, `InterfaceDefinition` and `TraitDefinition` objects can be nodes
 *
 * All nodes labels are HTML tables
 */
final class Node implements HasDotRepresentation
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
        if ($this->definition instanceof ClassDefinition) {
            return 'class';
        }
        if ($this->definition instanceof InterfaceDefinition) {
            return 'interface';
        }
        return 'trait';
    }
}
