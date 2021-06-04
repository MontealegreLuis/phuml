<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Variables\Variable;

/**
 * It checks the attributes and the constructor parameters of a class looking for external definitions
 *
 * An external definition is either a class or interface from a third party library, or a built-in
 * class
 *
 * In this case a `ClassDefinition` is added by default.
 * Although we don't really know if it's an interface since we don't have access to the source code
 */
final class ExternalAssociationsResolver extends ExternalDefinitionsResolver
{
    protected function resolveForClass(ClassDefinition $definition, Codebase $codebase): void
    {
        parent::resolveForClass($definition, $codebase);
        $this->resolveExternalAttributes($definition, $codebase);
        $this->resolveExternalConstructorParameters($definition, $codebase);
    }

    private function resolveExternalAttributes(ClassDefinition $definition, Codebase $codebase): void
    {
        array_map(function (Attribute $attribute) use ($codebase): void {
            if ($attribute->isAReference() && !$codebase->has($attribute->referenceName())) {
                $codebase->add($this->externalClass($attribute->referenceName()));
            }
        }, $definition->attributes());
    }

    private function resolveExternalConstructorParameters(ClassDefinition $definition, Codebase $codebase): void
    {
        array_map(function (Variable $parameter) use ($codebase): void {
            if ($parameter->isAReference() && !$codebase->has($parameter->referenceName())) {
                $codebase->add($this->externalClass($parameter->referenceName()));
            }
        }, $definition->constructorParameters());
    }
}
