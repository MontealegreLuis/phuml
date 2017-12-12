<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

class plDigraph implements plHasDotRepresentation
{
    /** @var plHasDotRepresentation[] */
    private $dotElements;

    /** @var plInterfaceGraphElements */
    private $interfaceElements;

    /** @var plClassGraphElements */
    private $classElements;

    public function __construct(
        plInterfaceGraphElements $interfaceElements,
        plClassGraphElements $classElements
    ) {
        $this->dotElements = [];
        $this->interfaceElements = $interfaceElements;
        $this->classElements = $classElements;
    }

    public function fromCodeStructure(array $structure): void
    {
        foreach ($structure as $definition) {
            if ($definition instanceof plPhpClass) {
                $this->classElementsFrom($definition, $structure);
            } else if ($definition instanceof plPhpInterface) {
                $this->interfaceElementsFrom($definition);
            }
        }
    }

    private function classElementsFrom(plPhpClass $class, array $structure): void
    {
        $this->dotElements = array_merge($this->dotElements, $this->classElements->extractFrom($class, $structure));
    }

    private function interfaceElementsFrom(plPhpInterface $interface): void
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
        $dotFormat = array_map(function (plHasDotRepresentation $element) {
            return $element->toDotLanguage();
        }, $this->dotElements);

        return implode('', $dotFormat);
    }

    private function graphId(): string
    {
        return sha1(mt_rand());
    }
}
