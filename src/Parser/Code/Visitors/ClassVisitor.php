<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;

/**
 * It builds a `ClassDefinition` and adds it to the `Codebase`
 */
final class ClassVisitor extends NodeVisitorAbstract
{
    public function __construct(private readonly ClassDefinitionBuilder $builder, private readonly Codebase $codebase)
    {
    }

    /** @return null|int|Node|Node[]  */
    public function leaveNode(Node $node): null|int|Node|array
    {
        if (! $node instanceof Class_) {
            return null;
        }
        if ($node->isAnonymous()) {
            return null;
        }

        $this->codebase->add($this->builder->build($node));

        return null;
    }
}
