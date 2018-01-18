<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Definition;

abstract class DefinitionBuilder
{
    /** @var Definition */
    protected $parent;

    /** @var string */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function extending(Definition $parent): DefinitionBuilder
    {
        $this->parent = $parent;

        return $this;
    }

    abstract public function build(): Definition;
}
