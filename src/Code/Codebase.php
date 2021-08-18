<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * Collection of all the classes and interfaces definitions found in a given directory
 */
final class Codebase
{
    /** @var Definition[] $definitions */
    private array $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    public function add(Definition $definition): void
    {
        $this->definitions[$definition->name()->fullName()] = $definition;
    }

    public function has(Name $name): bool
    {
        return isset($this->definitions[$name->fullName()]);
    }

    public function get(Name $name): Definition
    {
        return $this->definitions[$name->fullName()];
    }

    /** @return Definition[] */
    public function definitions(): array
    {
        return $this->definitions;
    }
}
