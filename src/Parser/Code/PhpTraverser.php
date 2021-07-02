<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use PhUml\Parser\Code\Builders\InterfaceDefinitionBuilder;
use PhUml\Parser\Code\Builders\TraitDefinitionBuilder;
use PhUml\Parser\Code\Visitors\ClassVisitor;
use PhUml\Parser\Code\Visitors\InterfaceVisitor;
use PhUml\Parser\Code\Visitors\TraitVisitor;

final class PhpTraverser
{
    protected Codebase $codebase;

    protected NodeTraverser $traverser;

    public function __construct(
        ClassDefinitionBuilder $classBuilder,
        InterfaceDefinitionBuilder $interfaceBuilder,
        TraitDefinitionBuilder $traitBuilder
    ) {
        $this->codebase = new Codebase();
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new ClassVisitor($classBuilder, $this->codebase));
        $this->traverser->addVisitor(new InterfaceVisitor($interfaceBuilder, $this->codebase));
        $this->traverser->addVisitor(new TraitVisitor($traitBuilder, $this->codebase));
    }

    /**
     * It will create a `Definition` from the given nodes.
     * It will add the `Definition` to the `Codebase`
     *
     * @param Stmt[] $nodes
     * @see PhpCodeParser::parse()
     */
    public function traverse(array $nodes): void
    {
        $this->traverser->traverse($nodes);
    }

    public function codebase(): Codebase
    {
        return $this->codebase;
    }
}
