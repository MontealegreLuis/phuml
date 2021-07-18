<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;

/**
 * Null object pattern implementation of `AssociationsBuilder`
 *
 * This class is used when the user ran the `phuml:diagram` command without the `associations` option.
 * Which means that no associations should be discovered
 */
final class NoAssociationsBuilder implements AssociationsBuilder
{
    public function fromAttributes(ClassDefinition $class, Codebase $codebase): array
    {
        return [];
    }

    public function fromConstructor(ClassDefinition $class, Codebase $codebase): array
    {
        return [];
    }
}
