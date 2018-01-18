<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Code;

/**
 * Collection of all the classes and interfaces definitions
 */
class Structure
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

    public function addClass(ClassDefinition $class): void
    {
        $this->classes[$class->name()] = $class;
    }

    public function addInterface(InterfaceDefinition $interface): void
    {
        $this->interfaces[$interface->name()] = $interface;
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
