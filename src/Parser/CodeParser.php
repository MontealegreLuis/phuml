<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\Structure;
use PhUml\Parser\Raw\TokenParser;

/**
 * It takes the files found by the `CodeFinder` and turns the into a code `Structure`
 *
 * A code `Structure` is a collection of `Definition`s (classes and interfaces)
 */
class CodeParser
{
    /** @var StructureBuilder */
    private $builder;

    /** @var TokenParser */
    private $parser;

    public function __construct(
        StructureBuilder $builder = null,
        TokenParser $parser = null
    ) {
        $this->builder = $builder ?? new StructureBuilder();
        $this->parser = $parser ?? new TokenParser();
    }

    public function parse(CodeFinder $finder): Structure
    {
        return $this->builder->buildFrom($this->parser->parse($finder));
    }
}
