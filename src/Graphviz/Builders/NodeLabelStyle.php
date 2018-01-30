<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

/**
 * It is a container for the partial files used to build the HTML label for the node
 */
abstract class NodeLabelStyle
{
    /** @var string */
    protected $attributes;

    /** @var string */
    protected $methods;

    public function __construct()
    {
        $this->setPartials();
    }

    public function attributes(): string
    {
        return $this->attributes;
    }

    public function methods(): string
    {
        return $this->methods;
    }

    abstract protected function setPartials(): void;
}
