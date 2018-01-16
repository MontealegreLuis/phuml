<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhUml\Code\Structure;
use PhUml\Parser\Builders\ClassBuilder;
use PhUml\Parser\Builders\InterfaceBuilder;

class TokenParser
{
    /** @var RawDefinitions */
    private $definitions;

    /** @var RelationsResolver */
    private $resolver;

    /** @var \PhpParser\Parser */
    private $parser;

    /** @var NodeTraverser */
    private $traverser;

    /** @var StructureBuilder */
    private $builder;

    public function __construct(
        RawDefinitions $definitions = null,
        RelationsResolver $resolver = null,
        StructureBuilder $builder = null
    ) {
        $this->definitions = $definitions ?? new RawDefinitions();
        $this->resolver = $resolver ?? new RelationsResolver();
        $this->builder = $builder ?? new StructureBuilder();
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new ClassVisitor($this->definitions, new ClassBuilder()));
        $this->traverser->addVisitor(new InterfaceVisitor($this->definitions, new InterfaceBuilder()));
    }

    public function parse(CodeFinder $finder): Structure
    {
        foreach ($finder->files() as $code) {
            $this->traverser->traverse($this->parser->parse($code));
        }
        $this->resolver->resolve($this->definitions);
        return $this->builder->buildFromDefinitions($this->definitions);
    }
}
