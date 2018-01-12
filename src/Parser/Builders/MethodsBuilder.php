<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Builders;

use PhpParser\Node\Stmt\ClassMethod;

class MethodsBuilder
{
    /** @param \PhpParser\Node\Stmt\Class_|\PhpParser\Node\Stmt\Interface_ $definition */
    public function build($definition): array
    {
        $methods = [];
        foreach ($definition->getMethods() as $method) {
            $methods[] = $this->buildMethod($method);
        }
        return $methods;
    }

    public function buildMethod(ClassMethod $method): array
    {
        return [
            $method->name,
            $this->resolveVisibility($method),
            $this->buildParameters($method->params),
            $method->getDocComment(),
        ];
    }

    private function resolveVisibility(ClassMethod $statement): string
    {
        switch (true) {
            case $statement->isPublic():
                return 'public';
            case $statement->isPrivate():
                return 'private';
            default:
                return 'protected';
        }
    }

    private function buildParameters(array $parameters): array
    {
        $params = [];
        /** @var \PhpParser\Node\Param $parameter */
        foreach ($parameters as $parameter) {
            $params[] = [
                $parameter->type,
                "\${$parameter->name}",
            ];
        }
        return $params;
    }
}
