<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * Both `ClassDefinition` and `InterfaceDefinition` objects identifiers are generated using the
 * function `spl_object_hash`
 */
trait ObjectHashIdentifier
{
    public function identifier(): string
    {
        return spl_object_hash($this);
    }
}
