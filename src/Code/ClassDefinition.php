<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * It represents a class definition
 *
 * It does not support class constants yet
 */
class ClassDefinition extends Definition
{
    /** @var Attribute[] */
    public $attributes;

    /** @var InterfaceDefinition[] */
    public $implements;

    public function __construct(
        string $name,
        array $attributes = [],
        array $methods = [],
        array $implements = [],
        ClassDefinition $extends = null
    ) {
        parent::__construct($name, $methods, $extends);
        $this->attributes = $attributes;
        $this->implements = $implements;
    }

    public function hasConstructor(): bool
    {
        return \count(array_filter($this->functions, function (Method $function) {
            return $function->isConstructor();
        })) === 1;
    }

    /** @return Variable[] */
    public function constructorParameters(): array
    {
        if (!$this->hasConstructor()) {
            return [];
        }

        $constructors = array_filter($this->functions, function (Method $method) {
            return $method->isConstructor();
        });

        return reset($constructors)->params;
    }

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }

    public function countAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->attributes, function (Attribute $attribute) use ($modifier) {
            return $attribute->modifier->equals($modifier);
        }));
    }

    public function countTypedAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->attributes, function (Attribute $attribute) use ($modifier) {
            return $attribute->isTyped() && $attribute->modifier->equals($modifier);
        }));
    }
}
