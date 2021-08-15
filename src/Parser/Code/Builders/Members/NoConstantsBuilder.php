<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassConst;
use PhUml\Code\Attributes\Constant;

final class NoConstantsBuilder implements ConstantsBuilder
{
    /**
     * @param ClassConst[] $classConstants
     * @return Constant[]
     */
    public function build(array $classConstants): array
    {
        return [];
    }
}
