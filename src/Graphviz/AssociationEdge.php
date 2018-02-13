<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

class AssociationEdge extends Edge
{
    public function dotTemplate(): string
    {
        return 'association';
    }
}
