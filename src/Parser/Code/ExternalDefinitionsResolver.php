<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

/**
 * It checks the parent of a definition and the interfaces it implements looking for external
 * definitions
 *
 * An external definition is either a class or interface from a third party library, or a built-in
 * class
 */
class ExternalDefinitionsResolver
{
    public function resolve(Codebase $codebase): void
    {
        foreach ($codebase->definitions() as $definition) {
            if ($definition instanceof ClassDefinition) {
                $this->resolveForClass($definition, $codebase);
            } elseif ($definition instanceof InterfaceDefinition) {
                $this->resolveForInterface($definition, $codebase);
            } elseif ($definition instanceof TraitDefinition) {
                $this->resolveForTrait($definition, $codebase);
            }
        }
    }

    protected function resolveForClass(ClassDefinition $definition, Codebase $codebase): void
    {
        $this->resolveExternalInterfaces($definition->interfaces(), $codebase);
        $this->resolveExternalTraits($definition->traits(), $codebase);
        $this->resolveExternalParentClass($definition, $codebase);
    }

    protected function resolveForInterface(InterfaceDefinition $definition, Codebase $codebase): void
    {
        $this->resolveExternalInterfaces($definition->parents(), $codebase);
    }

    private function resolveForTrait(TraitDefinition $trait, Codebase $codebase): void
    {
        $this->resolveExternalTraits($trait->traits(), $codebase);
    }

    /** @param \PhUml\Code\Name[] $interfaces */
    private function resolveExternalInterfaces(array $interfaces, Codebase $codebase): void
    {
        array_map(function (Name $interface) use ($codebase) {
            if (!$codebase->has($interface)) {
                $codebase->add($this->externalInterface($interface));
            }
        }, $interfaces);
    }

    /** @param \PhUml\Code\Name[] $interfaces */
    private function resolveExternalTraits(array $traits, Codebase $codebase): void
    {
        array_map(function (Name $trait) use ($codebase) {
            if (!$codebase->has($trait)) {
                $codebase->add($this->externalTrait($trait));
            }
        }, $traits);
    }

    private function resolveExternalParentClass(ClassDefinition $definition, Codebase $codebase): void
    {
        if (!$definition->hasParent()) {
            return;
        }
        $parent = $definition->parent();
        if (!$codebase->has($parent)) {
            $codebase->add($this->externalClass($parent));
        }
    }

    protected function externalInterface(Name $name): InterfaceDefinition
    {
        return new InterfaceDefinition($name);
    }

    protected function externalClass(Name $name): ClassDefinition
    {
        return new ClassDefinition($name);
    }

    protected function externalTrait(Name $name): TraitDefinition
    {
        return new TraitDefinition($name);
    }
}
