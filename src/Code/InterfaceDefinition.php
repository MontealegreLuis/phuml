<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Code;

use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

class InterfaceDefinition extends Definition implements HasNodeIdentifier
{
    use ObjectHashIdentifier;

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }
}
