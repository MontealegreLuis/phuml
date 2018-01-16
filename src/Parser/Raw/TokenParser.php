<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\Raw\Visitors\ClassVisitor;
use PhUml\Parser\Raw\Visitors\InterfaceVisitor;

class TokenParser
{
    /** @var Parser */
    private $parser;

    /** @var NodeTraverser */
    private $traverser;

    /** @var ExternalDefinitionsResolver */
    private $resolver;

    /** @var RawDefinitions */
    private $definitions;

    public function __construct(
        Parser $parser = null,
        NodeTraverser $traverser = null,
        ExternalDefinitionsResolver $resolver = null
    ) {
        $this->definitions = new RawDefinitions();
        $this->parser = $parser ?? (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $this->traverser = $traverser ?? $this->defaultTraverser();
        $this->resolver = $resolver ?? new ExternalDefinitionsResolver();
    }

    private function defaultTraverser(): NodeTraverser
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ClassVisitor($this->definitions));
        $traverser->addVisitor(new InterfaceVisitor($this->definitions));
        return $traverser;
    }

    public function parse(CodeFinder $finder): RawDefinitions
    {
        foreach ($finder->files() as $code) {
            $this->traverser->traverse($this->parser->parse($code));
        }
        $this->resolver->resolve($this->definitions);
        return $this->definitions;
    }
}
