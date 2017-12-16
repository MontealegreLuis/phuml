<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

class InterfaceDefinition implements HasNodeIdentifier
{
    use ObjectHashIdentifier;

    /** @var string */
    public $name;

    /** @var Method[] */
    public $functions;

    /** @var InterfaceDefinition */
    public $extends;

    public function __construct(string $name, array $functions = [], $extends = null)
    {
        $this->name = $name;
        $this->functions = $functions;
        $this->extends = $extends;
    }

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }
}
