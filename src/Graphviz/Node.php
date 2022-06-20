<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\TraitDefinition;

/**
 * `ClassDefinition`, `InterfaceDefinition` and `TraitDefinition` objects can be nodes
 *
 * All nodes labels are HTML tables
 */
final class Node implements HasDotRepresentation
{
    public function __construct(private readonly Definition $definition)
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
        return match ($this->definition::class) {
            ClassDefinition::class => 'class',
            InterfaceDefinition::class => 'interface',
            TraitDefinition::class => 'trait',
            default => 'enum',
        };
    }
}
