<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;

/**
 * It extracts an `TraitDefinition` and adds it to the `Codebase`
 */
final class TraitVisitor extends NodeVisitorAbstract
{
    public function __construct(private TraitDefinitionBuilder $builder, private Codebase $codebase)
    {
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Trait_) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
