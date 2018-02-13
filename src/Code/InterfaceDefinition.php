<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;
use PhUml\Graphviz\HasDotRepresentation;

/**
 * It represents an interface definition
 */
class InterfaceDefinition extends Definition implements HasDotRepresentation
{
    /**
     * It only counts the constants of an interface, since interfaces are not allowed to have
     * attributes
     *
     * The method name is `hasAttributes` since in a class diagram, constants are shown in the same
     * block as the instance variables (attributes)
     */
    public function hasAttributes(): bool
    {
        return \count($this->constants) > 0;
    }

    public function dotTemplate(): string
    {
        return 'interface';
    }
}
