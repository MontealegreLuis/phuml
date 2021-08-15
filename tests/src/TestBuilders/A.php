<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

final class A
{
    public static function classNamed(string $name): ClassDefinition
    {
        return new ClassDefinition(new Name($name));
    }

    public static function interfaceNamed(string $name): InterfaceDefinition
    {
        return new InterfaceDefinition(new Name($name));
    }

    public static function traitNamed(string $name): TraitDefinition
    {
        return new TraitDefinition(new Name($name));
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

    public static function method(string $name): MethodBuilder
    {
        return new MethodBuilder($name);
    }

    public static function codeParserConfiguration(): CodeParserConfigurationBuilder
    {
        return new CodeParserConfigurationBuilder();
    }

    public static function graphvizProcessor(): GraphvizProcessorBuilder
    {
        return new GraphvizProcessorBuilder();
    }

    public static function graphvizConfiguration(): GraphvizConfigurationBuilder
    {
        return new GraphvizConfigurationBuilder();
    }

    public static function statisticsGeneratorConfiguration(): StatisticsGeneratorConfigurationBuilder
    {
        return new StatisticsGeneratorConfigurationBuilder();
    }

    public static function classDiagramConfiguration(): ClassDiagramConfigurationBuilder
    {
        return new ClassDiagramConfigurationBuilder();
    }

    public static function digraphConfiguration(): DigraphConfigurationBuilder
    {
        return new DigraphConfigurationBuilder();
    }

    public static function membersBuilder(): MembersBuilderBuilder
    {
        return new MembersBuilderBuilder();
    }

    public static function typeBuilderBuilder(): TypeBuilderBuilder
    {
        return new TypeBuilderBuilder();
    }

    public static function codeFinderConfiguration(): CodeFinderConfigurationBuilder
    {
        return new CodeFinderConfigurationBuilder();
    }
}
