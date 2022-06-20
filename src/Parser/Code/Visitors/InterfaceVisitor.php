<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;

/**
 * It builds an `InterfaceDefinition` and adds it to the `Codebase`
 */
final class InterfaceVisitor extends NodeVisitorAbstract
{
    public function __construct(private readonly InterfaceDefinitionBuilder $builder, private readonly Codebase $codebase)
    {
    }

    /** @return null|int|Node|Node[]  */
    public function leaveNode(Node $node): null|int|Node|array
    {
        if ($node instanceof Interface_) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
