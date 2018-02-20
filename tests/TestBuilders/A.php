<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;

class A
{
    public static function classNamed(string $name): ClassDefinition
    {
        return new ClassDefinition($name);
    }

    public static function interfaceNamed(string $name): InterfaceDefinition
    {
        return new InterfaceDefinition($name);
    }

    public static function numericIdClassNamed(string $name): NumericIdClass
    {
        return new NumericIdClass($name);
    }

    public static function numericIdInterfaceNamed(string $name): NumericIdInterface
    {
        return new NumericIdInterface($name);
    }

    public static function class(string $name): ClassBuilder
    {
        return new ClassBuilder($name);
    }

    public static function interface(string $name): InterfaceBuilder
    {
        return new InterfaceBuilder($name);
    }

    public static function parameter(string $name): ParameterBuilder
    {
        return new ParameterBuilder($name);
    }
}
