<?php declare(strict_types=1);
/**
 * PHP version 8.0
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
final class FilteredConstantsBuilder implements ConstantsBuilder
{
    /** @var string[] */
    private const TYPES = [
        'integer' => 'int',
        'double' => 'float',
        'string' => 'string',
    ];

    public function __construct(
        private VisibilityBuilder $visibilityBuilder,
        private VisibilityFilters $visibilityFilters
    ) {
    }

    /**
     * @param Node[] $classAttributes
     * @return Constant[]
     */
    public function build(array $classAttributes): array
    {
        $constants = array_filter($classAttributes, static fn ($attribute): bool => $attribute instanceof ClassConst);

        return array_map(fn (ClassConst $constant): Constant => new Constant(
            (string) $constant->consts[0]->name,
            TypeDeclaration::from($this->determineType($constant->consts[0])),
            $this->visibilityBuilder->build($constant)
        ), $this->visibilityFilters->apply($constants));
    }

    private function determineType(Const_ $constant): ?string
    {
        if (property_exists($constant->value, 'value')) {
            return self::TYPES[\gettype($constant->value->value)];
        }
        if (! $constant->value instanceof ConstFetch) {
            return null;
        }
        if (! \in_array($constant->value->name->parts[0], ['true', 'false'], true)) {
            return null;
        }
        return 'bool'; // It's an expression
    }
}
