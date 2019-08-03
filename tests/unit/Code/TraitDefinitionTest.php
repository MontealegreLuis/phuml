<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\HasAttributes;
use PhUml\ContractTests\DefinitionTest;
use PhUml\ContractTests\WithAttributesTests;

class TraitDefinitionTest extends DefinitionTest
{
    use WithAttributesTests;

    /** @param \PhUml\Code\Methods\Method[] */
    protected function definition(array $methods = []): Definition
    {
        return new TraitDefinition(Name::from('ADefinition'), $methods);
    }

    /** @param Attribute[] $attributes */
    protected function definitionWithAttributes(array $attributes = []): HasAttributes
    {
        return new TraitDefinition(Name::from('ATraitWithAttributes'), [], $attributes);
    }
}
