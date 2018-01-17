<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhUml\Parser\Raw\RawDefinition;
use PhUml\Parser\Raw\RawDefinitions;

/**
 * It checks the parent of a definition and the interfaces it implements looking for external
 * definitions
 *
 * An external definition is a class or interface that belongs to a third party library or
 * a PHP's built-in class
 */
class ExternalDefinitionsResolver
{
    public function resolve(RawDefinitions $definitions): void
    {
        foreach ($definitions->all() as $definition) {
            if ($definition->isClass()) {
                $this->resolveForClass($definitions, $definition);
            } else {
                $this->resolveForInterface($definitions, $definition);
            }
        }
    }

    private function resolveForClass(RawDefinitions $definitions, RawDefinition $definition): void
    {
        foreach ($definition->interfaces() as $interface) {
            if (!$definitions->has($interface)) {
                $definitions->addExternalInterface($interface);
            }
        }
        $this->resolveParentClass($definitions, $definition);
    }

    private function resolveForInterface(RawDefinitions $definitions, RawDefinition $definition): void
    {
        $this->resolveParentInterface($definitions, $definition);
    }

    private function resolveParentClass(RawDefinitions $definitions, RawDefinition $definition): void
    {
        if ($definition->hasParent()) {
            $parent = $definition->parent();
            if (!$definitions->has($parent)) {
                $definitions->addExternalClass($parent);
            }
        }
    }

    private function resolveParentInterface(RawDefinitions $definitions, RawDefinition $definition): void
    {
        if ($definition->hasParent()) {
            $parent = $definition->parent();
            if (!$definitions->has($parent)) {
                $definitions->addExternalInterface($parent);
            }
        }
    }
}
