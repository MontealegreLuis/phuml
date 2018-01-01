<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

class Definitions
{
    private static $classDefaults = [
        'attributes' => [],
        'functions' => [],
        'implements' => [],
        'extends' => null,
    ];

    private static $interfaceDefaults = [
        'functions' => [],
        'extends' => null,
    ];

    /** @var array */
    private $definitions;

    public function __construct()
    {
        $this->definitions = [];
    }

    public function add(array $definition): void
    {
        if ($this->isClass($definition)) {
            $this->addClass($definition);
        } elseif ($this->isInterface($definition)) {
            $this->addInterface($definition);
        }
    }

    public function get(string $name)
    {
        return $this->definitions[$name] ?? null;
    }

    public function addExternalClass(string $name): void
    {
        $this->definitions[$name] = array_merge(['class' => $name], self::$classDefaults);
    }

    public function addExternalInterface(string $name): void
    {
        $this->definitions[$name] = array_merge(['interface' => $name], self::$interfaceDefaults);
    }

    public function has(string $definitionName): bool
    {
        return isset($this->definitions[$definitionName]);
    }

    public function all(): array
    {
        return $this->definitions;
    }

    /** @return string[] */
    public function interfaces(array $definition): array
    {
        return $definition['implements'];
    }

    public function hasParent(array $definition): bool
    {
        return isset($definition['extends']);
    }

    public function parent(array $definition): ?string
    {
        return $definition['extends'] ?? null;
    }

    public function isClass(array $definition): bool
    {
        return isset($definition['class']);
    }

    public function isInterface(array $definition): bool
    {
        return isset($definition['interface']);
    }

    private function addClass(array $definition): void
    {
        $this->definitions[$definition['class']] = [
            'class' => $definition['class'],
            'attributes' => $definition['attributes'] ?? self::$classDefaults['attributes'],
            'functions' => $definition['functions'] ?? self::$classDefaults['functions'],
            'implements' => $definition['implements'] ?? self::$classDefaults['implements'],
            'extends' => $definition['extends'] ?? self::$classDefaults['extends'],
        ];
    }

    private function addInterface(array $definition): void
    {
        $this->definitions[$definition['interface']] = [
            'interface' => $definition['interface'],
            'functions' => $definition['functions'] ?? self::$interfaceDefaults['functions'],
            'extends' => $definition['extends'] ?? self::$interfaceDefaults['extends'],
        ];
    }
}
