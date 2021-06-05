<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt\ClassConst;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It builds an array of `Constants` for either a `ClassDefinition` or an `InterfaceDefinition`
 */
class ConstantsBuilder
{
    /** @var string[] */
    private static $types = [
        'integer' => 'int',
        'double' => 'float',
        'string' => 'string',
    ];

    /**
     * @param Node[] $classAttributes
     * @return Constant[]
     */
    public function build(array $classAttributes): array
    {
        $constants = array_filter($classAttributes, static function ($attribute): bool {
            return $attribute instanceof ClassConst;
        });
        return array_map(function (ClassConst $constant): Constant {
            return new Constant(
                (string) $constant->consts[0]->name,
                TypeDeclaration::from($this->determineType($constant->consts[0]))
            );
        }, $constants);
    }

    private function determineType(Const_ $constant): ?string
    {
        if (property_exists($constant->value, 'value')) {
            return self::$types[\gettype($constant->value->value)];
        }
        if ($constant->value instanceof ConstFetch
            && \in_array($constant->value->name->parts[0], ['true', 'false'], true)) {
            return 'bool';
        }
        return null; // It's an expression
    }
}
