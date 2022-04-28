<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use BadMethodCallException;
use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\CanBeAbstract;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Parameters\Parameter;
use PhUml\Code\Properties\Constant;
use PhUml\Code\Properties\HasConstants;
use PhUml\Code\Properties\HasProperties;
use PhUml\Code\Properties\Property;
use PhUml\Code\Properties\WithConstants;
use PhUml\Code\Properties\WithProperties;

/**
 * It represents a class definition
 */
final class ClassDefinition extends Definition implements HasProperties, HasConstants, CanBeAbstract, UseTraits
{
    use WithProperties;
    use WithConstants;
    use WithTraits;

    /**
     * @param Method[] $methods
     * @param Constant[] $constants
     * @param Property[] $properties
     * @param Name[] $interfaces
     * @param Name[] $traits
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $constants = [],
        private readonly ?Name $parent = null,
        array $properties = [],
        private readonly array $interfaces = [],
        array $traits = [],
        private readonly bool $isAttribute = false
    ) {
        parent::__construct($name, $methods);
        $this->constants = $constants;
        $this->properties = $properties;
        $this->traits = $traits;
    }

    /**
     * This method is used by the `AssociationsBuilder` class to discover associations with other
     * classes via the constructor
     *
     * @return Parameter[]
     * @see \PhUml\Graphviz\Builders\AssociationsBuilder::fromProperties() for more details
     */
    public function constructorParameters(): array
    {
        $constructors = array_filter($this->methods, static fn (Method $method): bool => $method->isConstructor());
        $constructor = reset($constructors);

        return $constructor === false ? [] : $constructor->parameters();
    }

    /**
     * This method is used to build the `Summary` of a `Codebase`
     *
     * @see Summary::propertiesSummary() for more details
     */
    public function countPropertiesByVisibility(Visibility $visibility): int
    {
        return \count(array_filter(
            $this->properties,
            static fn (Property $property): bool => $property->hasVisibility($visibility)
        ));
    }

    /**
     * This method is used to build the `Summary` of a `Codebase`
     *
     * @see Summary::propertiesSummary() for more details
     */
    public function countTypedPropertiesByVisibility(Visibility $visibility): int
    {
        return \count(array_filter(
            $this->properties,
            static fn (Property $property): bool =>
                $property->hasTypeDeclaration() && $property->hasVisibility($visibility)
        ));
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
        if ($this->parent === null) {
            throw new BadMethodCallException('This class does not have a parent class');
        }
        return $this->parent;
    }

    /**
     * It is used by the `ClassGraphBuilder` to determine if an inheritance association should be
     * created
     *
     * @see \PhUml\Graphviz\Builders\ClassGraphBuilder::extractFrom() for more details
     */
    public function hasParent(): bool
    {
        return $this->parent !== null;
    }

    /**
     * This method is used when the commands are called with the option `hide-empty-blocks`
     *
     * It counts both the properties and the constants of a class
     *
     * @see Definition::hasProperties() for more details
     */
    public function hasProperties(): bool
    {
        return \count($this->constants) + \count($this->properties) > 0;
    }

    /**
     * This method is used to determine if the class name should be shown in italics
     */
    public function isAbstract(): bool
    {
        return array_filter($this->methods(), static fn (Method $method): bool => $method->isAbstract()) !== [];
    }

    public function isAttribute(): bool
    {
        return $this->isAttribute;
    }
}
