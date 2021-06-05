<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhpParser\Parser;
use PhUml\Code\Codebase;
use PhUml\Parser\CodeFinder;

abstract class PhpParser
{
    /** @var Parser */
    private $parser;

    /** @var PhpTraverser */
    private $traverser;

    public function __construct(Parser $parser, PhpTraverser $traverser)
    {
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    public function parse(CodeFinder $finder): Codebase
    {
        foreach ($finder->files() as $code) {
            /** @var \PhpParser\Node\Stmt[] $nodes Since the parser is run in throw errors mode */
            $nodes = $this->parser->parse($code);
            $this->traverser->traverse($nodes);
        }
        return $this->traverser->codebase();
    }
}
