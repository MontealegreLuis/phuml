<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

/**
 * It represents the collection of the classes and interfaces of the codebase being analyzed
 *
 * This collection of raw definitions is used to build a code `Structure`
 *
 * @see \PhUml\Parser\StructureBuilder For the details on how a `Structure` is built from a `RawDefinitions` object
 */
class RawDefinitions
{
    /** @var RawDefinition[] */
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    public function add(RawDefinition $definition): void
    {
        $this->definitions[$definition->name()] = $definition;
    }

    public function get(string $name): ?RawDefinition
    {
        return $this->definitions[$name] ?? null;
    }

    public function addExternalClass(string $name): void
    {
        $this->definitions[$name] = RawDefinition::externalClass($name);
    }

    public function addExternalInterface(string $name): void
    {
        $this->definitions[$name] = RawDefinition::externalInterface($name);
    }

    public function has(string $definitionName): bool
    {
        return isset($this->definitions[$definitionName]);
    }

    /** @return RawDefinition[] */
    public function all(): array
    {
        return $this->definitions;
    }
}
