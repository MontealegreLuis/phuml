<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;

/**
 * It builds a `TraitDefinition` and adds it to the `Codebase`
 */
final class TraitVisitor extends NodeVisitorAbstract
{
    public function __construct(private readonly TraitDefinitionBuilder $builder, private readonly Codebase $codebase)
    {
    }

    /** @return null|int|Node|Node[]  */
    public function leaveNode(Node $node): null|int|Node|array
    {
        if ($node instanceof Trait_) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
