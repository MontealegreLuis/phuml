<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Codebase;

/**
 * The traverser will create a `Definition` from the nodes of an AST
 *
 * It will use its visitors to build either a class, an interface or a trait
 *
 * @see \PhUml\Parser\Code\Visitors\ClassVisitor
 * @see \PhUml\Parser\Code\Visitors\InterfaceVisitor
 * @see \PhUml\Parser\Code\Visitors\TraitVisitor
 */
abstract class PhpTraverser
{
    /** @var \PhUml\Code\Codebase */
    protected $codebase;

    /** @var \PhpParser\NodeTraverser */
    protected $traverser;

    /**
     * It will create a `Definition` from the given nodes.
     * It will add the `Definition` to the `Codebase`
     *
     * @param \PhpParser\Node[] $nodes
     * @see PhpParser::parse()
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
