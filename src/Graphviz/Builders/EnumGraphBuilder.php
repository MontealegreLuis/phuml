<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\Codebase;
use PhUml\Code\EnumDefinition;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\HasDotRepresentation;
use PhUml\Graphviz\Node;

final class EnumGraphBuilder
{
    /** @return HasDotRepresentation[] */
    public function extractFrom(EnumDefinition $enum, Codebase $codebase): array
    {
        $dotElements = [new Node($enum)];

        foreach ($enum->interfaces() as $interface) {
            $dotElements[] = Edge::implementation($codebase->get($interface), $enum);
        }

        foreach ($enum->traits() as $usedTrait) {
            $dotElements[] = Edge::use($codebase->get($usedTrait), $enum);
        }

        return $dotElements;
    }
}
