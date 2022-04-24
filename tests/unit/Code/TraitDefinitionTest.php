<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Methods\Method;
use PhUml\Code\Properties\HasProperties;
use PhUml\Code\Properties\Property;
use PhUml\ContractTests\DefinitionTest;
use PhUml\ContractTests\WithPropertiesTests;

final class TraitDefinitionTest extends DefinitionTest
{
    use WithPropertiesTests;

    /** @param Method[] */
    protected function definition(array $methods = []): Definition
    {
        return new TraitDefinition(new Name('ADefinition'), $methods);
    }

    /** @param Property[] $properties */
    protected function definitionWithProperties(array $properties = []): HasProperties
    {
        return new TraitDefinition(new Name('ATraitWithProperties'), [], $properties);
    }
}
