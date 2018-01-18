<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

class A
{
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
