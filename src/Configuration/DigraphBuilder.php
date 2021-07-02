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
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\Code\RelationshipsResolver;
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

    public function __construct(DigraphConfiguration $configuration)
    {
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
        $associationsBuilder = $this->configuration->extractAssociations()
            ? new EdgesBuilder()
            : new NoAssociationsBuilder();

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
        return new CodeParser($this->tokenParser(), $this->externalDefinitionsResolvers());
    }

    protected function tokenParser(): PhpCodeParser
    {
        $parserBuilder = new ParserBuilder();
        if ($this->configuration->hideAttributes()) {
            $parserBuilder->excludeAttributes();
        }
        if ($this->configuration->hideMethods()) {
            $parserBuilder->excludeMethods();
        }
        if ($this->configuration->hidePrivate()) {
            $parserBuilder->excludePrivateMembers();
        }
        if ($this->configuration->hideProtected()) {
            $parserBuilder->excludeProtectedMembers();
        }
        return $parserBuilder->build();
    }

    /** @return RelationshipsResolver[] */
    private function externalDefinitionsResolvers(): array
    {
        $resolvers = [new ExternalDefinitionsResolver()];
        if ($this->configuration->extractAssociations()) {
            $resolvers[] = new ExternalAssociationsResolver();
        }
        return $resolvers;
    }
}
