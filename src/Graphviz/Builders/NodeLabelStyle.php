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
class NodeLabelStyle
{
    /** @var string */
    private $attributes;

    /** @var string */
    private $methods;

    public function __construct(string $attributes = null, string $methods = null)
    {
        $this->attributes = $attributes ?? '_attributes.html.twig';
        $this->methods = $methods ?? '_methods.html.twig';
    }

    public function attributes(): string
    {
        return $this->attributes;
    }

    public function methods(): string
    {
        return $this->methods;
    }
}
