<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;

/**
 * It extracts an `InterfaceDefinition` and adds it to the `Codebase`
 */
final class InterfaceVisitor extends NodeVisitorAbstract
{
    /** @var Codebase */
    private $codebase;

    /** @var InterfaceDefinitionBuilder */
    private $builder;

    public function __construct(InterfaceDefinitionBuilder $builder, Codebase $codebase)
    {
        $this->builder = $builder;
        $this->codebase = $codebase;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Interface_) {
            $this->codebase->add($this->builder->build($node));
        }
        return null;
    }
}
