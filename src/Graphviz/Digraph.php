<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Graphviz;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Structure;

class Digraph implements HasDotRepresentation
{
    /** @var HasDotRepresentation[] */
    private $dotElements;

    /** @var InterfaceGraphElements */
    private $interfaceElements;

    /** @var ClassGraphElements */
    private $classElements;

    public function __construct(
        InterfaceGraphElements $interfaceElements,
        ClassGraphElements $classElements
    ) {
        $this->dotElements = [];
        $this->interfaceElements = $interfaceElements;
        $this->classElements = $classElements;
    }

    public function fromCodeStructure(Structure $structure): void
    {
        foreach ($structure->definitions() as $definition) {
            if ($definition instanceof ClassDefinition) {
                $this->classElementsFrom($definition, $structure);
            } else if ($definition instanceof InterfaceDefinition) {
                $this->interfaceElementsFrom($definition);
            }
        }
    }

    private function classElementsFrom(ClassDefinition $class, Structure $structure): void
    {
        $this->dotElements = array_merge($this->dotElements, $this->classElements->extractFrom($class, $structure));
    }

    private function interfaceElementsFrom(InterfaceDefinition $interface): void
    {
        $this->dotElements = array_merge($this->dotElements, $this->interfaceElements->extractFrom($interface));
    }

    public function toDotLanguage(): string
    {
        return "digraph \"{$this->graphId()}\" {
splines = true;
overlap = false;
mindist = 0.6;
{$this->elementsToDotLanguage()}}";
    }

    private function elementsToDotLanguage(): string
    {
        $dotFormat = array_map(function (HasDotRepresentation $element) {
            return $element->toDotLanguage();
        }, $this->dotElements);

        return implode('', $dotFormat);
    }

    private function graphId(): string
    {
        return sha1(mt_rand());
    }
}
