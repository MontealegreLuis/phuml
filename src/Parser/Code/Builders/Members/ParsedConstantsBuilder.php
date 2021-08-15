<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Stmt\ClassConst;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It builds an array of `Constants` for either a `ClassDefinition` or an `InterfaceDefinition`
 */
final class ParsedConstantsBuilder implements ConstantsBuilder
{
    /** @var string[] */
    private const TYPES = [
        'integer' => 'int',
        'double' => 'float',
        'string' => 'string',
    ];

    public function __construct(private VisibilityBuilder $visibilityBuilder)
    {
    }

    /**
     * @param ClassConst[] $classConstants
     * @return Constant[]
     */
    public function build(array $classConstants): array
    {
        return array_map(fn (ClassConst $constant): Constant => new Constant(
            (string) $constant->consts[0]->name,
            TypeDeclaration::from($this->determineType($constant->consts[0])),
            $this->visibilityBuilder->build($constant)
        ), $classConstants);
    }

    private function determineType(Const_ $constant): ?string
    {
        if (property_exists($constant->value, 'value')) {
            return self::TYPES[\gettype($constant->value->value)];
        }
        if (! $constant->value instanceof ConstFetch) {
            return null;
        }
        return 'bool';
    }
}
