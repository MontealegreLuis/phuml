<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\ClassConst;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It builds an array of `Constants` for either a `ClassDefinition` or an `InterfaceDefinition`
 */
class ConstantsBuilder
{
    private static $types = [
        'integer' => 'int',
        'double' => 'float',
        'string' => 'string',
    ];

    /**
     * @param \PhpParser\Node[] $classAttributes
     * @return Constant[]
     */
    public function build(array $classAttributes): array
    {
        $constants = array_filter($classAttributes, function ($attribute) {
            return $attribute instanceof ClassConst;
        });
        return array_map(function (ClassConst $constant) {
            return new Constant(
                $constant->consts[0]->name,
                TypeDeclaration::from($this->determineType($constant->consts[0]))
            );
        }, $constants);
    }

    private function determineType(Const_ $constant): ?string
    {
        if ($constant->value instanceof Scalar) {
            return self::$types[\gettype($constant->value->value)];
        }
        if ($constant->value instanceof ConstFetch
            && \in_array($constant->value->name->parts[0], ['true', 'false'], true)) {
            return 'bool';
        }
        return null; // It's an expression
    }
}
