<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhpParser\NodeTraverser;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\Visitors\ClassVisitor;
use PhUml\Parser\Raw\Visitors\InterfaceVisitor;

class Php5Traverser extends PhpTraverser
{
    public function __construct(RawClassBuilder $rawClassBuilder, RawInterfaceBuilder $rawInterfaceBuilder)
    {
        $this->definitions = new RawDefinitions();
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new ClassVisitor($this->definitions, $rawClassBuilder));
        $this->traverser->addVisitor(new InterfaceVisitor($this->definitions, $rawInterfaceBuilder));
    }
}
