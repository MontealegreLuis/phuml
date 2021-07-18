<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;

/**
 * It extracts a `ClassDefinition` and adds it to the `Codebase`
 */
final class ClassVisitor extends NodeVisitorAbstract
{
    public function __construct(private ClassDefinitionBuilder $builder, private Codebase $codebase)
    {
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Class_ && ! $node->isAnonymous()) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
