<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Variable;

class ClassGraphElements
{
    /** @var HasDotRepresentation[] */
    private $dotElements = [];

    /** @var bool[] */
    private $associations = [];

    /** @var bool */
    private $createAssociations;

    /** @var NodeLabelBuilder */
    private $labelBuilder;

    /** @var HasNodeIdentifier[] */
    private $structure;

    public function __construct(bool $createAssociations, NodeLabelBuilder $labelBuilder)
    {
        $this->createAssociations = $createAssociations;
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * @param HasNodeIdentifier[] $structure
     * @return HasDotRepresentation[]
     */
    public function extractFrom(ClassDefinition $class, array $structure): array
    {
        $this->dotElements = [];
        $this->associations = [];
        $this->structure = $structure;

        if ($this->createAssociations) {
            $this->addElementsForAttributes($class);
            $this->addElementsForParameters($class);
        }

        $this->dotElements[] = new Node($class, $this->labelBuilder->labelForClass($class));

        if ($class->hasParent()) {
            $this->dotElements[] = Edge::inheritance($class->extends, $class);
        }

        foreach ($class->implements as $interface) {
            $this->dotElements[] = Edge::implementation($interface, $class);
        }

        return $this->dotElements;
    }

    /** @return HasDotRepresentation[] */
    private function addElementsForAttributes(ClassDefinition $class): void
    {
        /** @var \PhUml\Code\Attribute $attribute */
        foreach ($class->attributes as $attribute) {
            $this->addAssociationForVariable($class, $attribute);
        }
    }

    /** @return HasDotRepresentation[] */
    private function addElementsForParameters(ClassDefinition $class): void
    {
        if (!$class->hasConstructor()) {
            return;
        }
        foreach ($class->constructorParameters() as $parameter) {
            $this->addAssociationForVariable($class, $parameter);
        }
    }

    private function addAssociationForVariable(ClassDefinition $class, Variable $attribute): void
    {
        if ($this->needAssociation($attribute)) {
            $this->dotElements[] = Edge::association($this->structure[(string)$attribute->type], $class);
            $this->associations[strtolower($attribute->type)] = true;
        }
    }

    private function needAssociation(Variable $attribute): bool
    {
        return $attribute->hasType() && !$attribute->isBuiltIn() && !$this->isAssociationResolved($attribute->type);
    }

    private function isAssociationResolved(string $type): bool
    {
        return array_key_exists(strtolower($type), $this->associations);
    }
}
