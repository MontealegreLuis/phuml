<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\HasAttributes;
use PhUml\Code\Methods\Method;
use PhUml\ContractTests\DefinitionTest;
use PhUml\ContractTests\WithAttributesTests;

final class TraitDefinitionTest extends DefinitionTest
{
    use WithAttributesTests;

    /** @param Method[] */
    protected function definition(array $methods = []): Definition
    {
        return new TraitDefinition(new Name('ADefinition'), $methods);
    }

    /** @param Attribute[] $attributes */
    protected function definitionWithAttributes(array $attributes = []): HasAttributes
    {
        return new TraitDefinition(new Name('ATraitWithAttributes'), [], $attributes);
    }
}
