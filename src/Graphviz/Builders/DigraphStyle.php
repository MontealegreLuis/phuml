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
abstract class DigraphStyle
{
    /** @var string */
    protected $theme;

    /** @var string */
    protected $attributes;

    /** @var string */
    protected $methods;

    public function __construct(string $theme = 'phuml')
    {
        $this->theme = "{$theme}.html.twig";
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

    public function theme(): string
    {
        return $this->theme;
    }

    abstract protected function setPartials(): void;
}
