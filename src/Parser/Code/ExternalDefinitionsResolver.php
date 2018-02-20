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
            }
        }
    }

    private function resolveForClass(ClassDefinition $definition, Codebase $codebase): void
    {
        $this->resolveExternalInterfaces($definition->interfaces(), $codebase);
        $this->resolveExternalParentClass($definition, $codebase);
    }

    private function resolveForInterface(InterfaceDefinition $definition, Codebase $codebase): void
    {
        $this->resolveExternalInterfaces($definition->parents(), $codebase);
    }

    /**
     * @param string[] $interfaces
     * @param Codebase $codebase
     */
    private function resolveExternalInterfaces(array $interfaces, Codebase $codebase): void
    {
        foreach ($interfaces as $interface) {
            if (!$codebase->has($interface)) {
                $codebase->add($this->externalInterface($interface));
            }
        }
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

    protected function externalInterface(string $name): InterfaceDefinition
    {
        return new InterfaceDefinition($name);
    }

    protected function externalClass(string $name): ClassDefinition
    {
        return new ClassDefinition($name);
    }
}
