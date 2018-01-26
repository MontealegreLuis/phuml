<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;
use PhUml\Templates\TemplateEngine;

class ClassDiagramBuilder
{
    private $configuration;

    public static function from(ClassDiagramConfiguration $configuration): ClassDiagramBuilder
    {
        return new ClassDiagramBuilder($configuration);
    }

    private function __construct(ClassDiagramConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function generator(): ClassDiagramGenerator
    {
        $associationsBuilder = $this->configuration->extractAssociations() ? new EdgesBuilder() : new NoAssociationsBuilder();
        $digraphProcessor = new GraphvizProcessor(
            new ClassGraphBuilder(new NodeLabelBuilder(new TemplateEngine()), $associationsBuilder)
        );
        $imageProcessor = $this->configuration->isDotProcessor() ? new DotProcessor() : new NeatoProcessor();

        return new ClassDiagramGenerator(new CodeParser(), $digraphProcessor, $imageProcessor);
    }

    public function codeFinder(): CodeFinder
    {
        return $this->configuration->searchRecursively() ? new CodeFinder() : new NonRecursiveCodeFinder();
    }
}
