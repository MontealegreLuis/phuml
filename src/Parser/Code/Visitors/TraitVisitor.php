<?php
/**
 * PHP version 7.1
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
class TraitVisitor extends NodeVisitorAbstract
{
    /** @var TraitDefinitionBuilder */
    private $builder;

    /** @var Codebase */
    private $codebase;

    public function __construct(TraitDefinitionBuilder $builder, Codebase $codebase)
    {
        $this->builder = $builder;
        $this->codebase = $codebase;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Trait_) {
            $this->codebase->add($this->builder->build($node));
        }
    }
}
