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

/**
 * It traverses the AST of all the files and interfaces found by the `CodeFinder` and builds a
 * `RawDefinitions` object
 *
 * In order to create the collection of raw definitions it uses two visitors
 *
 * - The `ClassVisitor` which builds `RawDefinitions` for classes
 * - The `InterfaceVisitor` which builds `RawDefinitions` for interfaces
 *
 * It will call the `ExternalDefinitionsResolver` to add generic `RawDefinition`s for classes and
 * interfaces that do not belong directly to the current codebase.
 * These external definitions are either built-in or from third party libraries
 */
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
        ExternalDefinitionsResolver $resolver = null,
        RawDefinitions $definitions = null
    ) {
        $this->definitions = $definitions ?? new RawDefinitions();
        $this->parser = $parser ?? (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $this->traverser = $traverser ?? $this->defaultTraverser();
        $this->resolver = $resolver ?? new ExternalDefinitionsResolver();
    }

    public function parse(CodeFinder $finder): RawDefinitions
    {
        foreach ($finder->files() as $code) {
            $this->traverser->traverse($this->parser->parse($code));
        }
        $this->resolver->resolve($this->definitions);
        return $this->definitions;
    }

    private function defaultTraverser(): NodeTraverser
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ClassVisitor($this->definitions));
        $traverser->addVisitor(new InterfaceVisitor($this->definitions));
        return $traverser;
    }
}
