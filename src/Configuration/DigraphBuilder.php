<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\Builders\AttributesBuilder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\MethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\ExternalDefinitionsResolver;
use PhUml\Parser\Raw\RawDefinitions;
use PhUml\Parser\Raw\TokenParser;
use PhUml\Parser\Raw\Visitors\ClassVisitor;
use PhUml\Parser\Raw\Visitors\InterfaceVisitor;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

class DigraphBuilder
{
    /** @var DigraphConfiguration */
    protected $configuration;

    public function codeFinder(): CodeFinder
    {
        return $this->configuration->searchRecursively() ? new CodeFinder() : new NonRecursiveCodeFinder();
    }

    protected function digraphProcessor(): GraphvizProcessor
    {
        $associationsBuilder = $this->configuration->extractAssociations() ? new EdgesBuilder() : new NoAssociationsBuilder();
        $digraphProcessor = new GraphvizProcessor(
            new ClassGraphBuilder(new NodeLabelBuilder(new TemplateEngine()), $associationsBuilder)
        );
        return $digraphProcessor;
    }

    protected function codeParser(): CodeParser
    {
        return new CodeParser(new StructureBuilder(), $this->tokenParser());
    }

    protected function tokenParser(): TokenParser
    {
        $definitions = new RawDefinitions();
        return new TokenParser(
            (new ParserFactory)->create(ParserFactory::PREFER_PHP5),
            $this->nodeTraverser($definitions),
            $definitions
        );
    }

    protected function nodeTraverser(RawDefinitions $definitions): NodeTraverser
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ClassVisitor(
            $definitions,
            new RawClassBuilder($this->attributesBuilder(), $this->methodsBuilder())
        ));
        $traverser->addVisitor(new InterfaceVisitor(
            $definitions,
            new RawInterfaceBuilder($this->methodsBuilder())
        ));

        return $traverser;
    }

    protected function attributesBuilder(): AttributesBuilder
    {
        return new AttributesBuilder($this->filters());
    }

    protected function methodsBuilder(): MethodsBuilder
    {
        return new MethodsBuilder($this->filters());
    }

    /** @return MembersFilter[] */
    protected function filters(): array
    {
        $filters = [];
        if ($this->configuration->hidePrivate()) {
            $filters[] = new PrivateMembersFilter();
        }
        if ($this->configuration->hideProtected()) {
            $filters[] = new ProtectedMembersFilter();
        }
        return $filters;
    }
}
