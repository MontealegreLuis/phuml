<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Graphviz\Styles\DefaultDigraphStyle;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\NonEmptyBlocksStyle;
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
        return new GraphvizProcessor(
            new ClassGraphBuilder($associationsBuilder),
            new InterfaceGraphBuilder(),
            new DigraphPrinter(new TemplateEngine(), $this->digraphStyle())
        );
    }

    protected function digraphStyle(): DigraphStyle
    {
        if ($this->configuration->hideEmptyBlocks()) {
            return new NonEmptyBlocksStyle($this->configuration->theme());
        }
        return new DefaultDigraphStyle($this->configuration->theme());
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
