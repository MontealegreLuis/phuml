<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Graphviz\Builders\NodeLabelBuilder;

class ClassNameLabelBuilder extends NodeLabelBuilder
{
    public function __construct()
    {
    }

    public function forClass(ClassDefinition $class): string
    {
        return $this->template($class);
    }

    public function forInterface(InterfaceDefinition $interface): string
    {
        return $this->template($interface);
    }

    private function template($classOrInterface): string
    {
        return "<<table><tr><td>{$classOrInterface->name}</td></tr></table>>";
    }
}
