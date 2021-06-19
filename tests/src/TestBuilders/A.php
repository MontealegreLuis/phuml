<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\NumericIdTrait;

final class A
{
    public static function classNamed(string $name): ClassDefinition
    {
        return new ClassDefinition(Name::from($name));
    }

    public static function interfaceNamed(string $name): InterfaceDefinition
    {
        return new InterfaceDefinition(Name::from($name));
    }

    public static function traitNamed(string $name): TraitDefinition
    {
        return new TraitDefinition(Name::from($name));
    }

    public static function numericIdClassNamed(string $name): NumericIdClass
    {
        return new NumericIdClass(Name::from($name));
    }

    public static function numericIdInterfaceNamed(string $name): NumericIdInterface
    {
        return new NumericIdInterface(Name::from($name));
    }

    public static function numericIdTraitNamed(string $name): NumericIdTrait
    {
        return new NumericIdTrait(Name::from($name));
    }

    public static function class(string $name): ClassBuilder
    {
        return new ClassBuilder($name);
    }

    public static function interface(string $name): InterfaceBuilder
    {
        return new InterfaceBuilder($name);
    }

    public static function trait(string $name): TraitBuilder
    {
        return new TraitBuilder($name);
    }

    public static function parameter(string $name): ParameterBuilder
    {
        return new ParameterBuilder($name);
    }

    public static function attribute(string $name): AttributeBuilder
    {
        return new AttributeBuilder($name);
    }

    public static function variable(string $name): VariableBuilder
    {
        return new VariableBuilder($name);
    }

    public static function method(): MethodBuilder
    {
        return new MethodBuilder();
    }
}
