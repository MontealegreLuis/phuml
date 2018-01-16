<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Parser\Definitions;
use PhUml\Parser\RawDefinition;
use PhUml\Parser\StructureBuilder;

class NumericIdStructureBuilder extends StructureBuilder
{
    protected function buildInterface(Definitions $definitions, RawDefinition $interface): InterfaceDefinition
    {
        return new NumericIdInterface(
            $interface->name(),
            $this->buildMethods($interface),
            $this->resolveRelatedInterface($definitions, $interface->parent())
        );
    }

    protected function buildClass(Definitions $definitions, RawDefinition $class): ClassDefinition
    {
        return new NumericIdClass(
            $class->name(),
            $this->buildAttributes($class),
            $this->buildMethods($class),
            $this->buildInterfaces($definitions, $class->interfaces()),
            $this->resolveParentClass($definitions, $class->parent())
        );
    }
}
