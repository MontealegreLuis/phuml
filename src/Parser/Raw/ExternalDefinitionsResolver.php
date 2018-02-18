<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

/**
 * It checks the parent of a definition and the interfaces it implements looking for external
 * definitions
 *
 * An external definition is either a class or interface from a third party library, or a built-in
 * class
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
        $this->resolveExternalInterfaces($definition->interfaces(), $definitions);
        $this->resolveParentClass($definitions, $definition);
    }

    private function resolveForInterface(RawDefinitions $definitions, RawDefinition $definition): void
    {
        $this->resolveExternalInterfaces($definition->parents(), $definitions);
    }

    /** @param string[] $interfaces */
    private function resolveExternalInterfaces(array $interfaces, RawDefinitions $definitions): void
    {
        foreach ($interfaces as $interface) {
            if (!$definitions->has($interface)) {
                $definitions->addExternalInterface($interface);
            }
        }
    }

    private function resolveParentClass(RawDefinitions $definitions, RawDefinition $definition): void
    {
        if (!$definition->hasParent()) {
            return;
        }
        $parent = $definition->parent();
        if (!$definitions->has($parent)) {
            $definitions->addExternalClass($parent);
        }
    }
}
