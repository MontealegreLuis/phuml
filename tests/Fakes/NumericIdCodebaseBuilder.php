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
            $this->builder->constants($interface),
            $this->builder->methods($interface),
            $this->resolveRelatedInterface($definitions, $interface->parent())
        );
    }

    protected function buildClass(RawDefinitions $definitions, RawDefinition $class): ClassDefinition
    {
        return new NumericIdClass(
            $class->name(),
            $this->builder->constants($class),
            $this->builder->methods($class),
            $this->resolveParentClass($definitions, $class->parent()),
            $this->builder->attributes($class),
            $this->buildInterfaces($definitions, $class->interfaces())
        );
    }
}
