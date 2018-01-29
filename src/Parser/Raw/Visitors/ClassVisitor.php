<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitorAbstract;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\RawDefinitions;

/**
 * It extracts the `RawDefinition` of a class and adds it to the collection of `RawDefinitions`
 */
class ClassVisitor extends NodeVisitorAbstract
{
    /** @var RawDefinitions */
    private $definitions;

    /** @var RawClassBuilder */
    private $builder;

    public function __construct(RawDefinitions $definitions, RawClassBuilder $builder)
    {
        $this->definitions = $definitions;
        $this->builder = $builder;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Class_) {
            $this->definitions->add($this->builder->build($node));
        }
    }
}
