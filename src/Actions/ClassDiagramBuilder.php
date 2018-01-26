<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;
use PhUml\Templates\TemplateEngine;

class ClassDiagramBuilder
{
    public static function from(ClassDiagramConfiguration $configuration): GenerateClassDiagram
    {
        $associationsBuilder = $configuration->extractAssociations() ? new EdgesBuilder() : new NoAssociationsBuilder();
        $digraphProcessor = new GraphvizProcessor(
            new ClassGraphBuilder(new NodeLabelBuilder(new TemplateEngine()), $associationsBuilder)
        );
        $imageProcessor = $configuration->isDotProcessor() ? new DotProcessor() : new NeatoProcessor();

        $action = new GenerateClassDiagram(new CodeParser(), $digraphProcessor, $imageProcessor);

        return $action;
    }
}
