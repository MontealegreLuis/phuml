<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\ContractTests\DefinitionTest;

class InterfaceDefinitionTest extends DefinitionTest
{
    protected function definition(
        array $constants = [],
        array $methods = [],
        Definition $parent = null
    ): Definition
    {
        return new InterfaceDefinition('ADefinition', $constants, $methods, $parent);
    }

    protected function parent(): Definition
    {
        return new InterfaceDefinition('ParentInterface');
    }
}
