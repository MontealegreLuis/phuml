<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Parser\Raw\RawDefinition;

/**
 * It builds the attributes and methods of both classes and interfaces
 */
class DefinitionMembersBuilder
{
    /** @return \PhUml\Code\Methods\Method[] */
    public function methods(RawDefinition $definition): array
    {
        return $definition->methods();
    }

    /** @return \PhUml\Code\Attributes\Attribute[] */
    public function attributes(RawDefinition $class): array
    {
        return $class->attributes();
    }

    /** @return \PhUml\Code\Attributes\Constant[] */
    public function constants(RawDefinition $definition): array
    {
        return $definition->constants();
    }
}
