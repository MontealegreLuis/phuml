<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\NodeTraverser;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;
use PhUml\Parser\Code\Visitors\ClassVisitor;
use PhUml\Parser\Code\Visitors\InterfaceVisitor;

class Php5Traverser extends PhpTraverser
{
    public function __construct(ClassDefinitionBuilder $classBuilder, InterfaceDefinitionBuilder $interfaceBuilder)
    {
        $this->codebase = new Codebase();
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new ClassVisitor($classBuilder, $this->codebase));
        $this->traverser->addVisitor(new InterfaceVisitor($interfaceBuilder, $this->codebase));
    }
}
