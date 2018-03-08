<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Variables\Variable;
use PhUml\Graphviz\Edge;

/**
 * It creates edges by inspecting a class
 *
 * 1. It creates edges by inspecting the attributes of a class
 * 2. It creates edges by inspecting the parameters of the constructor of a class
 */
class EdgesBuilder implements AssociationsBuilder
{
    /** @var bool[] */
    private $associations = [];

    /**
     * It creates an edge if the attribute
     *
     * - Has type information and it's not a PHP's built-in type
     * - The association hasn't already been resolved
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function fromAttributes(ClassDefinition $class, Codebase $codebase): array
    {
        return array_map(function (Variable $attribute) use ($class, $codebase) {
            return $this->addAssociation($class, $attribute, $codebase);
        }, array_filter($class->attributes(), function(Attribute $attribute) use ($class) {
            return $this->needAssociation($class, $attribute);
        }));
    }

    /**
     * It creates an edge if the constructor parameter
     *
     * - Has type information and it's not a PHP's built-in type
     * - The association hasn't already been resolved
     *
     * @return \PhUml\Graphviz\HasDotRepresentation[]
     */
    public function fromConstructor(ClassDefinition $class, Codebase $codebase): array
    {
        return array_map(function (Variable $attribute) use ($class, $codebase) {
            return $this->addAssociation($class, $attribute, $codebase);
        }, array_filter($class->constructorParameters(), function(Variable $parameter) use ($class) {
            return $this->needAssociation($class, $parameter);
        }));
    }

    private function addAssociation(ClassDefinition $class, Variable $attribute, Codebase $codebase): Edge
    {
        $this->markAssociationResolvedFor($class, $attribute);

        return Edge::association($codebase->get($attribute->referenceName()), $class);
    }

    private function needAssociation(ClassDefinition $class, Variable $attribute): bool
    {
        return $attribute->isAReference() && !$this->isAssociationResolved($class, $attribute);
    }

    private function isAssociationResolved(ClassDefinition $class, Variable $attribute): bool
    {
        return array_key_exists($this->associationKey($class, $attribute), $this->associations);
    }

    private function markAssociationResolvedFor(ClassDefinition $class, Variable $attribute): void
    {
        $this->associations[$this->associationKey($class, $attribute)] = true;
    }

    private function associationKey(ClassDefinition $class, Variable $attribute): string
    {
        return strtolower($class->name() . '.' . $attribute->type());
    }
}
