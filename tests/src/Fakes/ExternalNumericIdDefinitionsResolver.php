<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;
use PhUml\Parser\Code\ExternalDefinitionsResolver;

final class ExternalNumericIdDefinitionsResolver extends ExternalDefinitionsResolver
{
    protected function externalInterface(Name $name): InterfaceDefinition
    {
        return new NumericIdInterface($name);
    }

    protected function externalClass(Name $name): ClassDefinition
    {
        return new NumericIdClass($name);
    }
}
