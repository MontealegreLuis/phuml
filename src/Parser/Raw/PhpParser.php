<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;

abstract class PhpParser
{
    /** @var Parser */
    private $parser;

    /** @var NodeTraverser */
    private $traverser;

    /** @var RawDefinitions */
    protected $definitions;

    /** @var MembersFilter[] */
    protected $filters;

    /** @param MembersFilter[] $filters */
    public function __construct(Parser $parser, RawDefinitions $definitions, array $filters)
    {
        $this->definitions = $definitions;
        $this->parser = $parser;
        $this->filters = $filters;
        $this->traverser = $this->buildTraverser();
    }

    public function parse(CodeFinder $finder): RawDefinitions
    {
        foreach ($finder->files() as $code) {
            $this->traverser->traverse($this->parser->parse($code));
        }
        return $this->definitions;
    }

    abstract protected function buildTraverser(): NodeTraverser;
}
