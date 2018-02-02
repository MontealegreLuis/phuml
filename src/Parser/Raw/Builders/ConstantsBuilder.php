<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\ClassConst;

class ConstantsBuilder
{
    private static $types = [
        'integer' => 'int',
        'double' => 'float',
        'string' => 'string',
    ];

    /** @param \PhpParser\Node[] $classAttributes */
    public function build(array $classAttributes): array
    {
        $constants = array_filter($classAttributes, function ($attribute) {
            return $attribute instanceof ClassConst;
        });
        return array_map(function (ClassConst $constant) {
            return [
                "{$constant->consts[0]->name}",
                $this->determineType($constant->consts[0]),
            ];
        }, $constants);
    }

    private function determineType(Const_ $constant): ?string
    {
        if ($constant->value instanceof Scalar) {
            return self::$types[\gettype($constant->value->value)];
        }
        if ($constant->value instanceof ConstFetch) {
            if (\in_array($constant->value->name->parts[0], ['true', 'false'], true)) {
                return 'bool';
            }
        }
        return null; // It's an expression
    }
}
