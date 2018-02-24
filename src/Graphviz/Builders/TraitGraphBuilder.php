<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\Codebase;
use PhUml\Code\TraitDefinition;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;

class TraitGraphBuilder
{
    /**
     * It creates a node for a Trait
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function extractFrom(TraitDefinition $trait, Codebase $codebase): array
    {
        $dotElements = [new Node($trait)];

        foreach ($trait->traits() as $usedTrait) {
            $dotElements[] = Edge::use($codebase->get($usedTrait), $trait);
        }

        return $dotElements;
    }
}
