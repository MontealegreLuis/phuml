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
    /** @var ClassDefinition[] */
    private $classes;

    /** @var InterfaceDefinition[] */
    private $interfaces;

    public function __construct()
    {
        $this->classes = [];
        $this->interfaces = [];
    }

    public function add(Definition $definition): void
    {
        if ($definition instanceof ClassDefinition) {
            $this->classes[(string)$definition->name()] = $definition;
        } else {
            $this->interfaces[(string)$definition->name()] = $definition;
        }
    }

    public function has(string $name): bool
    {
        return isset($this->interfaces[$name]) || isset($this->classes[$name]);
    }

    public function get(string $name): Definition
    {
        return $this->interfaces[$name] ?? $this->classes[$name];
    }

    /** @return Definition[] */
    public function definitions(): array
    {
        return array_merge($this->classes, $this->interfaces);
    }
}
