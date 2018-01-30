<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhpParser\NodeTraverser;

abstract class PhpTraverser
{
    /** @var RawDefinitions */
    protected $definitions;

    /** @var NodeTraverser */
    protected $traverser;

    /** @param \PhpParser\Node[] */
    public function traverse(array $nodes): void
    {
        $this->traverser->traverse($nodes);
    }

    public function definitions(): RawDefinitions
    {
        return $this->definitions;
    }
}
