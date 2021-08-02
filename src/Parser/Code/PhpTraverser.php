<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhUml\Code\Codebase;

final class PhpTraverser
{
    public function __construct(
        private NodeTraverser $traverser,
        private Codebase $codebase,
    ) {
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
