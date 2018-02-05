<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

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
 * - is it abstract?
 * - doc block
 *
 * You can run one or more filters, the current available filters will exclude
 *
 * - protected methods
 * - private methods
 * - both protected and private if both filters are provided
 *
 * @see PrivateMembersFilter
 * @see ProtectedMembersFilter
 */
class MethodsBuilder extends MembersBuilder
{
    /** @param ClassMethod[] $classMethods */
    public function build(array $classMethods): array
    {
        return array_map(function (ClassMethod $method) {
            return $this->buildMethod($method);
        }, $this->runFilters($classMethods));
    }

    private function buildMethod(ClassMethod $method): array
    {
        return [
            $method->name,
            $this->resolveVisibility($method),
            $this->buildParameters($method->params, $method->getDocComment()),
            $method->isAbstract(),
            $method->isStatic(),
            $method->getDocComment(),
        ];
    }

    private function buildParameters(array $parameters, ?string $docBlock): array
    {
        return array_map(function (Param $parameter) use ($docBlock) {
            return [
                "\${$parameter->name}",
                $parameter->type,
                $docBlock,
            ];
        }, $parameters);
    }
}
