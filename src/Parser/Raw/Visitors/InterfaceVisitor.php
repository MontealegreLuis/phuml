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
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\RawDefinitions;

/**
 * It extracts the `RawDefinition` of a interface and adds it to the collection of `RawDefinitions`
 */
class InterfaceVisitor extends NodeVisitorAbstract
{
    /** @var RawDefinitions */
    private $definitions;

    /** @var RawInterfaceBuilder */
    private $builder;

    public function __construct(RawDefinitions $definitions, RawInterfaceBuilder $builder = null)
    {
        $this->definitions = $definitions;
        $this->builder = $builder ?? new RawInterfaceBuilder();
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Interface_) {
            $this->definitions->add($this->builder->build($node));
        }
    }
}
