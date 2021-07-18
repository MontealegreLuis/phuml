<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * Nodes in DOT language require a unique identifier in order to be able to create edges between them
 */
interface HasNodeIdentifier
{
    public function identifier(): string;
}
