<?php declare(strict_types=1);
/**
 * PHP version 7.2
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
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Parser\Code\ExternalAssociationsResolver;
use PhUml\Parser\Code\ExternalDefinitionsResolver;
use PhUml\Parser\Code\ParserBuilder;
use PhUml\Parser\Code\PhpParser;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

final class DigraphBuilder
{
    /** @var DigraphConfiguration */
    protected $configuration;

    /** @var ParserBuilder */
    protected $parserBuilder;

    public function __construct(DigraphConfiguration $configuration)
    {
        $this->parserBuilder = new ParserBuilder();
        $this->configuration = $configuration;
    }

    public function codeFinder(CodebaseDirectory $directory): CodeFinder
    {
        return $this->configuration->searchRecursively()
            ? SourceCodeFinder::recursive($directory)
            : SourceCodeFinder::nonRecursive($directory);
    }

    public function digraphProcessor(): GraphvizProcessor
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
            return DigraphStyle::withoutEmptyBlocks($this->configuration->theme());
        }
        return DigraphStyle::default($this->configuration->theme());
    }

    public function codeParser(): CodeParser
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
