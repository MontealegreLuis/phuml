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
use PhUml\Graphviz\Builders\TraitGraphBuilder;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Graphviz\Styles\DefaultDigraphStyle;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\NonEmptyBlocksStyle;
use PhUml\Parser\Code\ExternalAssociationsResolver;
use PhUml\Parser\Code\ExternalDefinitionsResolver;
use PhUml\Parser\Code\ParserBuilder;
use PhUml\Parser\Code\PhpParser;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
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
            new TraitGraphBuilder(),
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
        return new CodeParser($this->tokenParser(), $this->externalDefinitionsResolver());
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

    private function externalDefinitionsResolver(): ExternalDefinitionsResolver
    {
        if ($this->configuration->extractAssociations()) {
            return new ExternalAssociationsResolver();
        }
        return new ExternalDefinitionsResolver();
    }
}
