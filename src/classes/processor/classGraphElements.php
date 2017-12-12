<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

class plClassGraphElements
{
    /** @var plHasDotRepresentation[] */
    private $dotElements = [];

    /** @var bool[] */
    private $associations = [];

    /** @var bool */
    private $createAssociations;

    /** @var plNodeLabelBuilder */
    private $labelBuilder;

    /** @var plHasNodeIdentifier[] */
    private $structure;

    public function __construct(bool $createAssociations, plNodeLabelBuilder $labelBuilder)
    {
        $this->createAssociations = $createAssociations;
        $this->labelBuilder = $labelBuilder;
    }

    /**
     * @param plHasNodeIdentifier[] $structure
     * @return plHasDotRepresentation[]
     */
    public function extractFrom(plPhpClass $class, array $structure): array
    {
        $this->dotElements = [];
        $this->associations = [];
        $this->structure = $structure;

        if ($this->createAssociations) {
            $this->addElementsForAttributes($class);
            $this->addElementsForParameters($class);
        }

        $this->dotElements[] = new plNode($class, $this->labelBuilder->labelForClass($class));

        if ($class->hasParent()) {
            $this->dotElements[] = plEdge::inheritance($class->extends, $class);
        }

        foreach ($class->implements as $interface) {
            $this->dotElements[] = plEdge::implementation($interface, $class);
        }

        return $this->dotElements;
    }

    /** @return plHasDotRepresentation[] */
    private function addElementsForAttributes(plPhpClass $class): void
    {
        /** @var plPhpAttribute $attribute */
        foreach ($class->attributes as $attribute) {
            $this->addAssociationForVariable($class, $attribute);
        }
    }

    /** @return plHasDotRepresentation[] */
    private function addElementsForParameters(plPhpClass $class): void
    {
        /** @var plPhpFunction $function */
        foreach ($class->functions as $function) {
            // Create association if the function is the constructor and takes
            // other classes as parameters
            if ($function->isConstructor()) {
                /** @var plPhpVariable $param */
                foreach ($function->params as $param) {
                    $this->addAssociationForVariable($class, $param);
                }
            }
        }
    }

    private function addAssociationForVariable(plPhpClass $class, plPhpVariable $attribute): void
    {
        if ($this->needAssociation($attribute)) {
            $this->dotElements[] = plEdge::association($this->structure[(string)$attribute->type], $class);
            $this->associations[strtolower($attribute->type)] = true;
        }
    }

    private function needAssociation(plPhpVariable $attribute): bool
    {
        return $attribute->hasType() && !$attribute->isBuiltIn() && !$this->isAssociationResolved($attribute->type);
    }

    private function isAssociationResolved(string $type): bool
    {
        return array_key_exists(strtolower($type), $this->associations);
    }
}
