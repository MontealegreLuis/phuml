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

/**
 * It represents a class definition
 */
class ClassDefinition extends Definition implements CanBeAbstract
{
    /** @var Attribute[] */
    private $attributes;

    /** @var Name */
    protected $parent;

    /** @var Name[] */
    private $interfaces;

    /**
     * @param \PhUml\Code\Attributes\Constant[] $constants
     * @param Method[] $methods
     * @param Attribute[] $attributes
     * @param Name[] $interfaces
     */
    public function __construct(
        Name $name,
        array $constants = [],
        array $methods = [],
        Name $parent = null,
        array $attributes = [],
        array $interfaces = []
    ) {
        parent::__construct($name, $constants, $methods);
        $this->parent = $parent;
        $this->attributes = $attributes;
        $this->interfaces = $interfaces;
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
     * This method is used to build the `Summary` of a `Codebase`
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
     * This method is used to build the `Summary` of a `Codebase`
     *
     * @see Summary::attributesSummary() for more details
     */
    public function countTypedAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->attributes, function (Attribute $attribute) use ($modifier) {
            return $attribute->hasTypeDeclaration() && $attribute->hasVisibility($modifier);
        }));
    }

    /**
     * It is used by the `ClassGraphBuilder` to create the edges to represent implementation
     * associations
     *
     * @return Name[]
     * @see \PhUml\Graphviz\Builders\ClassGraphBuilder::extractFrom() for more details
     */
    public function interfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * It is used by the `ClassGraphBuilder` to create the edge to represent inheritance
     *
     * @see \PhUml\Graphviz\Builders\ClassGraphBuilder::extractFrom() for more details
     */
    public function parent(): Name
    {
        return $this->parent;
    }

    /**
     * It is used by the `ClassGraphBuilder` to determine if an inheritance association should be
     * created
     *
     * @return InterfaceDefinition[]
     * @see \PhUml\Graphviz\Builders\ClassGraphBuilder::extractFrom() for more details
     */
    public function hasParent(): bool
    {
        return $this->parent !== null;
    }

    /**
     * This method is used when the commands are called with the option `hide-empty-blocks`
     *
     * It counts both the attributes and the constants of a class
     *
     * @see Definition::hasAttributes() for more details
     */
    public function hasAttributes(): bool
    {
        return \count($this->constants()) + \count($this->attributes) > 0;
    }

    /**
     * This method is used to determine if the class name should be shown in italics
     */
    public function isAbstract(): bool
    {
        return \count(array_filter($this->methods(), function (Method $method) {
            return $method->isAbstract();
        })) > 0;
    }

    /** @return Attribute[] */
    public function attributes(): array
    {
        return $this->attributes;
    }

    private function hasConstructor(): bool
    {
        return \count(array_filter($this->methods, function (Method $function) {
            return $function->isConstructor();
        })) === 1;
    }
}
