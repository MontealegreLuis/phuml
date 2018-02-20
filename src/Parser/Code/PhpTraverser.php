<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Codebase;

abstract class PhpTraverser
{
    /** @var \PhUml\Code\Codebase */
    protected $codebase;

    /** @var \PhpParser\NodeTraverser */
    protected $traverser;

    /** @param \PhpParser\Node[] */
    public function traverse(array $nodes): void
    {
        $this->traverser->traverse($nodes);
    }

    public function codebase(): Codebase
    {
        return $this->codebase;
    }
}
