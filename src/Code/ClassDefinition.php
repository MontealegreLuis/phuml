<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\CanBeAbstract;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Graphviz\HasDotRepresentation;

/**
 * It represents a class definition
 */
class ClassDefinition extends Definition implements CanBeAbstract, HasDotRepresentation
{
    /** @var Attribute[] */
    private $attributes;

    /** @var InterfaceDefinition[] */
    private $implements;

    public function __construct(
        string $name,
        array $constants = [],
        array $methods = [],
        ClassDefinition $parent = null,
        array $attributes = [],
        array $implements = []
    ) {
        parent::__construct($name, $constants, $methods, $parent);
        $this->attributes = $attributes;
        $this->implements = $implements;
    }

    /**
     * This method is used by the `AssociationsBuilder` class to discover associations with other
     * classes via the constructor
     *
     * @return \PhUml\Code\Variables\Variable[]
     * @see \PhUml\Graphviz\Builders\AssociationsBuilder::fromAttributes() for more details
     */
    public function constructorParameters(): array
    {
        if (!$this->hasConstructor()) {
            return [];
        }

        $constructors = array_filter($this->methods, function (Method $method) {
            return $method->isConstructor();
        });

        return reset($constructors)->parameters();
    }

    /**
     * This method is used to build the `Summary` of a `Structure`
     *
     * @see Summary::attributesSummary() for more details
     */
    public function countAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->attributes, function (Attribute $attribute) use ($modifier) {
            return $attribute->hasVisibility($modifier);
        }));
    }

    /**
     * This method is used to build the `Summary` of a `Structure`
     *
     * @see Summary::attributesSummary() for more details
     */
    public function countTypedAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->attributes, function (Attribute $attribute) use ($modifier) {
            return $attribute->hasTypeDeclaration() && $attribute->hasVisibility($modifier);
        }));
    }

    public function isAbstract(): bool
    {
        return \count(array_filter($this->methods(), function (Method $method) {
            return $method->isAbstract();
        })) > 0;
    }

    /**
     * It counts both the attributes and the constants of a class
     */
    public function hasAttributes(): bool
    {
        return \count($this->constants) + \count($this->attributes) > 0;
    }

    /** @return Attribute[] */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /** @return InterfaceDefinition[] */
    public function implements(): array
    {
        return $this->implements;
    }

    private function hasConstructor(): bool
    {
        return \count(array_filter($this->methods, function (Method $function) {
            return $function->isConstructor();
        })) === 1;
    }

    public function dotTemplate(): string
    {
        return 'class';
    }
}
