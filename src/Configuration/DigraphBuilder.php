<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\DefaultLabelStyle;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Graphviz\Builders\NodeLabelStyle;
use PhUml\Graphviz\Builders\NonEmptyBlocksLabelStyle;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\ParserBuilder;
use PhUml\Parser\Raw\PhpParser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

class DigraphBuilder
{
    /** @var DigraphConfiguration */
    protected $configuration;

    /** @var ParserBuilder */
    protected $parserBuilder;

    public function __construct()
    {
        $this->parserBuilder = new ParserBuilder();
    }

    public function codeFinder(): CodeFinder
    {
        return $this->configuration->searchRecursively() ? new CodeFinder() : new NonRecursiveCodeFinder();
    }

    protected function digraphProcessor(): GraphvizProcessor
    {
        $associationsBuilder = $this->configuration->extractAssociations() ? new EdgesBuilder() : new NoAssociationsBuilder();
        $digraphProcessor = new GraphvizProcessor(
            new ClassGraphBuilder($this->nodeLabelBuilder(), $associationsBuilder)
        );
        return $digraphProcessor;
    }

    protected function nodeLabelBuilder(): NodeLabelBuilder
    {
        return new NodeLabelBuilder(new TemplateEngine(), $this->nodeLabelStyle());
    }

    protected function nodeLabelStyle(): NodeLabelStyle
    {
        return $this->configuration->hideEmptyBlocks() ? new NonEmptyBlocksLabelStyle() : new DefaultLabelStyle();
    }

    protected function codeParser(): CodeParser
    {
        return new CodeParser(new StructureBuilder(), $this->tokenParser());
    }

    protected function tokenParser(): PhpParser
    {
        $this->configureAttributes();
        $this->configureMethods();
        $this->configureFilters();
        return $this->parserBuilder->build();
    }

    private function configureAttributes(): void
    {
        if ($this->configuration->hideAttributes()) {
            $this->parserBuilder->excludeAttributes();
        }
    }

    private function configureMethods(): void
    {
        if ($this->configuration->hideMethods()) {
            $this->parserBuilder->excludeMethods();
        }
    }

    private function configureFilters(): void
    {
        if ($this->configuration->hidePrivate()) {
            $this->parserBuilder->excludePrivateMembers();
        }
        if ($this->configuration->hideProtected()) {
            $this->parserBuilder->excludeProtectedMembers();
        }
    }
}
