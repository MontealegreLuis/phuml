<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

class RelationsResolver
{
    public function resolve(Definitions $definitions): void
    {
        foreach ($definitions->all() as $definition) {
            if ($definitions->isClass($definition)) {
                $this->resolveForClass($definitions, $definition);
            } else {
                $this->resolveForInterface($definitions, $definition);
            }
        }
    }

    private function resolveForClass(Definitions $definitions, array $definition): void
    {
        foreach ($definitions->interfaces($definition) as $interface) {
            if (!$definitions->has($interface)) {
                $definitions->addExternalInterface($interface);
            }
        }
        $this->resolveParentClass($definitions, $definition);
    }

    private function resolveForInterface(Definitions $definitions, array $definition): void
    {
        $this->resolveParentInterface($definitions, $definition);
    }

    private function resolveParentClass(Definitions $definitions, array $definition): void
    {
        if ($definitions->hasParent($definition)) {
            $parent = $definitions->parent($definition);
            if (!$definitions->has($parent)) {
                $definitions->addExternalClass($parent);
            }
        }
    }

    private function resolveParentInterface(Definitions $definitions, array $definition): void
    {
        if ($definitions->hasParent($definition)) {
            $parent = $definitions->parent($definition);
            if (!$definitions->has($parent)) {
                $definitions->addExternalInterface($parent);
            }
        }
    }
}
