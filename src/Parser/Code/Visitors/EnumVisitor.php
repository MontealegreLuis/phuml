<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\EnumDefinitionBuilder;

/**
 * It builds an `EnumDefinition` and adds it to the `Codebase`
 */
final class EnumVisitor extends NodeVisitorAbstract
{
    public function __construct(private readonly EnumDefinitionBuilder $builder, private readonly Codebase $codebase)
    {
    }

    /** @return null|int|Node|Node[]  */
    public function leaveNode(Node $node): null|int|Node|array
    {
        if ($node instanceof Enum_) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
