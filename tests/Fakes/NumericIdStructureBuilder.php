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
use PhUml\Parser\StructureBuilder;

class NumericIdStructureBuilder extends StructureBuilder
{
    protected function buildInterface(Definitions $definitions, array $interface): InterfaceDefinition
    {
        return new NumericIdInterface(
            $interface['interface'],
            $this->buildMethods($interface),
            $this->resolveRelatedInterface($definitions, $interface['extends'])
        );
    }

    protected function buildClass(Definitions $definitions, array $class): ClassDefinition
    {
        return new NumericIdClass(
            $class['class'],
            $this->buildAttributes($class),
            $this->buildMethods($class),
            $this->buildInterfaces($definitions, $class['implements']),
            $this->resolveParentClass($definitions, $class['extends'])
        );
    }
}
