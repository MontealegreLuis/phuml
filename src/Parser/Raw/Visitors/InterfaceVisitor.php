<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Code\Codebase;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\RawDefinitions;

/**
 * It extracts an `InterfaceDefinition` and adds it to the `Codebase`
 */
class InterfaceVisitor extends NodeVisitorAbstract
{
    /** @var Codebase */
    private $codebase;

    /** @var RawInterfaceBuilder */
    private $builder;

    public function __construct(RawInterfaceBuilder $builder, Codebase $codebase)
    {
        $this->builder = $builder;
        $this->codebase = $codebase;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Interface_) {
            $this->codebase->add($this->builder->build($node));
        }
    }
}
