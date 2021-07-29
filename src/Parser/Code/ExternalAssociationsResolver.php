<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Name;
use PhUml\Code\Parameters\Parameter;

/**
 * It checks the attributes and the constructor parameters of a class looking for external definitions
 *
 * An external definition is either a class or interface from a third party library, or a built-in class or interface
 *
 * In the case of a third-party library or built-in type a `ClassDefinition` is added by default.
 * Although we don't really know if it's an interface or trait since we don't have access to the source code
 */
final class ExternalAssociationsResolver implements RelationshipsResolver
{
    public function resolve(Codebase $codebase): void
    {
        foreach ($codebase->definitions() as $definition) {
            if ($definition instanceof ClassDefinition) {
                $this->resolveForClass($definition, $codebase);
            }
        }
    }

    private function resolveForClass(ClassDefinition $definition, Codebase $codebase): void
    {
        $this->resolveExternalAttributes($definition, $codebase);
        $this->resolveExternalConstructorParameters($definition, $codebase);
    }

    private function resolveExternalAttributes(ClassDefinition $definition, Codebase $codebase): void
    {
        array_map(function (Attribute $attribute) use ($codebase): void {
            $this->resolveExternalAssociationsFromTypeNames($attribute->references(), $codebase);
        }, $definition->attributes());
    }

    private function resolveExternalConstructorParameters(ClassDefinition $definition, Codebase $codebase): void
    {
        array_map(function (Parameter $parameter) use ($codebase): void {
            $this->resolveExternalAssociationsFromTypeNames($parameter->references(), $codebase);
        }, $definition->constructorParameters());
    }

    /** @param Name[] $references */
    private function resolveExternalAssociationsFromTypeNames(array $references, Codebase $codebase): void
    {
        array_map(static function (Name $reference) use ($codebase): void {
            if ($codebase->has($reference)) {
                return;
            }
            $codebase->add(new ClassDefinition($reference));
        }, $references);
    }
}
