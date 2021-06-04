<?php
/**
 * PHP version 7.1
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
    /** @var ClassDefinitionBuilder */
    private $builder;

    /** @var Codebase */
    private $codebase;

    public function __construct(ClassDefinitionBuilder $builder, Codebase $codebase)
    {
        $this->builder = $builder;
        $this->codebase = $codebase;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Class_ && !$node->isAnonymous()) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
