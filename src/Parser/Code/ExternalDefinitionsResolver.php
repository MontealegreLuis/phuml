<?php declare(strict_types=1);
/**
 * PHP version 8.0
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
 * It checks the parent of a definition, the interfaces it implements, and the traits it uses
 * looking for external definitions
 *
 * An external definition is a class, trait or interface from a third party library, or a built-in class or interface
 */
final class ExternalDefinitionsResolver implements RelationshipsResolver
{
    public function resolve(Codebase $codebase): void
    {
        /** @var ClassDefinition|InterfaceDefinition|TraitDefinition $definition */
        foreach ($codebase->definitions() as $definition) {
            match (true) {
                $definition instanceof ClassDefinition => $this->resolveForClass($definition, $codebase),
                $definition instanceof InterfaceDefinition => $this->resolveInterfaces($definition->parents(), $codebase),
                default => $this->resolveTraits($definition->traits(), $codebase),
            };
        }
    }

    /**
     * It resolves for its parent class, its interfaces and traits
     */
    private function resolveForClass(ClassDefinition $definition, Codebase $codebase): void
    {
        $this->resolveInterfaces($definition->interfaces(), $codebase);
        $this->resolveTraits($definition->traits(), $codebase);
        $this->resolveExternalParentClass($definition, $codebase);
    }

    /** @param Name[] $interfaces */
    private function resolveInterfaces(array $interfaces, Codebase $codebase): void
    {
        array_map(static function (Name $interface) use ($codebase): void {
            if (! $codebase->has($interface)) {
                $codebase->add(new InterfaceDefinition($interface));
            }
        }, $interfaces);
    }

    /** @param Name[] $traits */
    private function resolveTraits(array $traits, Codebase $codebase): void
    {
        array_map(static function (Name $trait) use ($codebase): void {
            if (! $codebase->has($trait)) {
                $codebase->add(new TraitDefinition($trait));
            }
        }, $traits);
    }

    private function resolveExternalParentClass(ClassDefinition $definition, Codebase $codebase): void
    {
        if (! $definition->hasParent()) {
            return;
        }
        $parent = $definition->parent();
        if (! $codebase->has($parent)) {
            $codebase->add(new ClassDefinition($parent));
        }
    }
}
