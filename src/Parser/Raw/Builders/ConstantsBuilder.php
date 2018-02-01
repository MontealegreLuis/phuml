<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Stmt\ClassConst;

class ConstantsBuilder
{
    /** @param \PhpParser\Node[] $classAttributes */
    public function build(array $classAttributes): array
    {
        $constants = array_filter($classAttributes, function ($attribute) {
            return $attribute instanceof ClassConst;
        });
        return array_map(function (ClassConst $constant) {
            return [
                "{$constant->consts[0]->name}",
            ];
        }, $constants);
    }
}
