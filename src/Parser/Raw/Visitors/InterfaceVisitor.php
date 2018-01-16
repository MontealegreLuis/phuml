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
use PhUml\Parser\Raw\Builders\InterfaceBuilder;
use PhUml\Parser\Raw\RawDefinitions;

class InterfaceVisitor extends NodeVisitorAbstract
{
    /** @var RawDefinitions */
    private $definitions;

    /** @var InterfaceBuilder */
    private $builder;

    public function __construct(RawDefinitions $definitions, InterfaceBuilder $builder = null)
    {
        $this->definitions = $definitions;
        $this->builder = $builder ?? new InterfaceBuilder();
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Interface_) {
            $this->definitions->add($this->builder->build($node));
        }
    }
}
