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
        'constants' => [],
        'methods' => [],
        'implements' => [],
        'extends' => null,
    ];

    private static $interfaceDefaults = [
        'constants' => [],
        'methods' => [],
        'extends' => [],
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
            'constants' => $definition['constants'] ?? self::$classDefaults['constants'],
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
            'constants' => $definition['constants'] ?? self::$interfaceDefaults['constants'],
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

    /**
     * It is used by the `CodebaseBuilder` to create the definition for the parent of a class.
     * It is also used by the `ExternalDefinitionsResolver` to create an external definition for the
     * parent class, if needed
     *
     * @see \PhUml\Parser\CodebaseBuilder::resolveParentClass() for more details
     * @see ExternalDefinitionsResolver::resolveParentClass() for more details
     */
    public function parent(): ?string
    {
        return $this->definition['extends'] ?? null;
    }

    /**
     * It is used by the `CodebaseBuilder` to create the definitions for the parents of an interface.
     * It is also used by the `ExternalDefinitionsResolver` to create external definitions for the
     * parent interfaces, if needed
     *
     * @see \PhUml\Parser\CodebaseBuilder::buildInterface() for more details
     * @see ExternalDefinitionsResolver::resolveForInterface() for more details
     */
    public function parents(): array
    {
        return $this->definition['extends'] ?? [];
    }

    public function hasParents(): bool
    {
        return !empty($this->definition['extends']);
    }

    public function isClass(): bool
    {
        return isset($this->definition['class']);
    }

    public function isInterface(): bool
    {
        return isset($this->definition['interface']);
    }

    /** @return \PhUml\Code\Attributes\Attribute[] */
    public function attributes(): array
    {
        return $this->definition['attributes'];
    }

    /** @return \PhUml\Code\Methods\Method[] */
    public function methods(): array
    {
        return $this->definition['methods'];
    }

    /** @return \PhUml\Code\Attributes\Constant[] */
    public function constants(): array
    {
        return $this->definition['constants'];
    }
}
