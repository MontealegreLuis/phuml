<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

/**
 * A raw definition is a wrapper for an associative array of strings used to build `Definition`s
 *
 * @see \PhUml\Parser\Raw\Builders\RawClassBuilder To check how a `RawDefinition` for a class is built
 * @see \PhUml\Parser\Raw\Builders\RawInterfaceBuilder To check how a `RawDefinition` for an interface is built
 */
class RawDefinition
{
    private static $classDefaults = [
        'attributes' => [],
        'methods' => [],
        'implements' => [],
        'extends' => null,
    ];

    private static $interfaceDefaults = [
        'methods' => [],
        'extends' => null,
    ];

    /** @var array */
    private $definition;

    private function __construct(array $definition)
    {
        $this->definition = $definition;
    }

    public static function class(array $definition): RawDefinition
    {
        return new RawDefinition([
            'class' => $definition['class'],
            'attributes' => $definition['attributes'] ?? self::$classDefaults['attributes'],
            'methods' => $definition['methods'] ?? self::$classDefaults['methods'],
            'implements' => $definition['implements'] ?? self::$classDefaults['implements'],
            'extends' => $definition['extends'] ?? self::$classDefaults['extends'],
        ]);
    }

    public static function interface(array $definition): RawDefinition
    {
        return new RawDefinition([
            'interface' => $definition['interface'],
            'methods' => $definition['methods'] ?? self::$interfaceDefaults['methods'],
            'extends' => $definition['extends'] ?? self::$interfaceDefaults['extends'],
        ]);
    }

    public static function externalClass(string $name): RawDefinition
    {
        return new RawDefinition(array_merge(['class' => $name], self::$classDefaults));
    }

    public static function externalInterface(string $name): RawDefinition
    {
        return new RawDefinition(array_merge(['interface' => $name], self::$interfaceDefaults));
    }

    public function name(): string
    {
        return $this->definition['class'] ?? $this->definition['interface'];
    }

    /** @return string[] */
    public function interfaces(): array
    {
        return $this->definition['implements'];
    }

    public function hasParent(): bool
    {
        return isset($this->definition['extends']);
    }

    public function parent(): ?string
    {
        return $this->definition['extends'] ?? null;
    }

    public function isClass(): bool
    {
        return isset($this->definition['class']);
    }

    public function isInterface(): bool
    {
        return isset($this->definition['interface']);
    }

    public function attributes(): array
    {
        return $this->definition['attributes'];
    }

    public function methods(): array
    {
        return $this->definition['methods'];
    }
}
