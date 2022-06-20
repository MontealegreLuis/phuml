<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\EnumDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

/**
 * It looks for external definitions from the parent of a definition, the interfaces it implements, and the traits it
 * uses
 *
 * An external definition is a class, trait or interface from a third party library, or a built-in class or interface
 */
final class ExternalDefinitionsResolver implements RelationshipsResolver
{
    public function resolve(Codebase $codebase): void
    {
        /** @var ClassDefinition|InterfaceDefinition|TraitDefinition|EnumDefinition $definition */
        foreach ($codebase->definitions() as $definition) {
            match ($definition::class) {
                ClassDefinition::class => $this->resolveForClass($definition, $codebase),
                EnumDefinition::class => $this->resolveForEnum($definition, $codebase),
                InterfaceDefinition::class => $this->resolveInterfaces($definition->parents(), $codebase),
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

    private function resolveForEnum(EnumDefinition $definition, Codebase $codebase): void
    {
        $this->resolveInterfaces($definition->interfaces(), $codebase);
        $this->resolveTraits($definition->traits(), $codebase);
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
