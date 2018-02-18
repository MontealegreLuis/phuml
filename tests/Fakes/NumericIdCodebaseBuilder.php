<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Parser\Raw\RawDefinitions;
use PhUml\Parser\Raw\RawDefinition;
use PhUml\Parser\CodebaseBuilder;

class NumericIdCodebaseBuilder extends CodebaseBuilder
{
    protected function buildInterface(RawDefinitions $definitions, RawDefinition $interface): InterfaceDefinition
    {
        return new NumericIdInterface(
            $interface->name(),
            $interface->constants(),
            $interface->methods(),
            $this->resolveRelatedInterface($definitions, $interface->parent())
        );
    }

    protected function buildClass(RawDefinitions $definitions, RawDefinition $class): ClassDefinition
    {
        return new NumericIdClass(
            $class->name(),
            $class->constants(),
            $class->methods(),
            $this->resolveParentClass($definitions, $class->parent()),
            $class->attributes(),
            $this->buildInterfaces($definitions, $class->interfaces())
        );
    }
}
