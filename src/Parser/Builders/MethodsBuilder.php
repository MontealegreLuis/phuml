<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Builders;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * It builds an array with the meta-information of a method
 *
 * The generated array has the following structure
 *
 * - name
 * - visibility
 * - parameters
 *    - name
 *    - type
 * - doc block
 */
class MethodsBuilder
{
    /** @param \PhpParser\Node\Stmt\Class_|\PhpParser\Node\Stmt\Interface_ $definition */
    public function build($definition): array
    {
        return array_map(function (ClassMethod $method) {
            return $this->buildMethod($method);
        }, $definition->getMethods());
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
        return array_map(function (Param $parameter) {
            return [
                "\${$parameter->name}",
                $parameter->type,
            ];
        }, $parameters);
    }
}
