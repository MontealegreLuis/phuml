<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\Method;

/**
 * It will ignore the methods of a definition. It will produce diagrams without methods.
 */
final class NoMethodsBuilder implements MethodsBuilder
{
    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function build(array $methods): array
    {
        return [];
    }
}
