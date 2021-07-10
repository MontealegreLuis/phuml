<?php declare(strict_types=1);
/**
 * PHP version 7.4
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
use PhUml\Parser\CodeFinder;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

final class DigraphBuilder
{
    private DigraphConfiguration $configuration;

    public function __construct(DigraphConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function codeFinder(): CodeFinder
    {
        return $this->configuration->searchRecursively()
            ? SourceCodeFinder::recursive()
            : SourceCodeFinder::nonRecursive();
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

    private function digraphStyle(): DigraphStyle
    {
        $theme = $this->configuration->theme();

        if ($this->configuration->hideEmptyBlocks()) {
            return DigraphStyle::withoutEmptyBlocks($theme);
        }
        return DigraphStyle::default($theme);
    }
}
