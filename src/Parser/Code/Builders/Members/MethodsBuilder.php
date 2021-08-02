<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\UseStatements;

/**
 * It builds an array with `Method`s for a `ClassDefinition`, an `InterfaceDefinition` or a
 * `TraitDefinition`
 *
 * It can run one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
interface MethodsBuilder
{
    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function build(array $methods, UseStatements $useStatements): array;
}
