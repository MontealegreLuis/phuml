<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * Collection of all the classes and interfaces definitions found in a given directory
 */
class Codebase
{
    /** @var array Definition */
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    public function add(Definition $definition): void
    {
        $this->definitions[(string)$definition->name()] = $definition;
    }

    public function has(Name $name): bool
    {
        return isset($this->definitions[(string)$name]);
    }

    public function get(Name $name): Definition
    {
        return $this->definitions[(string)$name];
    }

    /** @return Definition[] */
    public function definitions(): array
    {
        return $this->definitions;
    }
}
