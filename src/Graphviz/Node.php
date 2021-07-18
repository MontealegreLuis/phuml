<?php declare(strict_types=1);
/**
 * PHP version 8.0
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
    public function __construct(private Definition $definition)
    {
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
