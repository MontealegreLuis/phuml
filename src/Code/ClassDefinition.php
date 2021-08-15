<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use BadMethodCallException;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Attributes\HasAttributes;
use PhUml\Code\Attributes\HasConstants;
use PhUml\Code\Attributes\WithAttributes;
use PhUml\Code\Attributes\WithConstants;
use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\CanBeAbstract;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Parameters\Parameter;

/**
 * It represents a class definition
 */
final class ClassDefinition extends Definition implements HasAttributes, HasConstants, CanBeAbstract, UseTraits
{
    use WithAttributes;
    use WithConstants;
    use WithTraits;

    /** @var Name[] */
    private array $interfaces;

    /**
     * @param Method[] $methods
     * @param Constant[] $constants
     * @param Attribute[] $attributes
     * @param Name[] $interfaces
     * @param Name[] $traits
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $constants = [],
        protected ?Name $parent = null,
        array $attributes = [],
        array $interfaces = [],
        array $traits = []
    ) {
        parent::__construct($name, $methods);
        $this->constants = $constants;
        $this->attributes = $attributes;
        $this->interfaces = $interfaces;
        $this->traits = $traits;
    }

    /**
     * This method is used by the `AssociationsBuilder` class to discover associations with other
     * classes via the constructor
     *
     * @return Parameter[]
     * @see \PhUml\Graphviz\Builders\AssociationsBuilder::fromAttributes() for more details
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
     * @see Summary::attributesSummary() for more details
     */
    public function countAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter(
            $this->attributes,
            static fn (Attribute $attribute): bool => $attribute->hasVisibility($modifier)
        ));
    }

    /**
     * This method is used to build the `Summary` of a `Codebase`
     *
     * @see Summary::attributesSummary() for more details
     */
    public function countTypedAttributesByVisibility(Visibility $modifier): int
    {
        return \count(array_filter(
            $this->attributes,
            static fn (Attribute $attribute): bool =>
                $attribute->hasTypeDeclaration() && $attribute->hasVisibility($modifier)
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
     * It counts both the attributes and the constants of a class
     *
     * @see Definition::hasAttributes() for more details
     */
    public function hasAttributes(): bool
    {
        return \count($this->constants) + \count($this->attributes) > 0;
    }

    /**
     * This method is used to determine if the class name should be shown in italics
     */
    public function isAbstract(): bool
    {
        return array_filter($this->methods(), static fn (Method $method): bool => $method->isAbstract()) !== [];
    }
}
