<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhUml\Code\Attributes\Constant;

/**
 * It builds an array of `Constants` for either a `ClassDefinition` or an `InterfaceDefinition`
 */
interface ConstantsBuilder
{
    /**
     * @param Node[] $classAttributes
     * @return Constant[]
     */
    public function build(array $classAttributes): array;
}
