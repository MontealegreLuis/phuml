<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use PhUml\Code\Structure;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\HtmlLabelStyle;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Graphviz\Digraph;
use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

class GraphvizProcessor extends Processor
{
    /** @var Digraph */
    private $digraph;

    public function __construct(bool $createAssociations, Digraph $digraph = null)
    {
        $labelBuilder = new NodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../Graphviz/templates')
        ), new HtmlLabelStyle());
        $classElements = new ClassGraphBuilder($createAssociations, $labelBuilder);
        $interfaceElements = new InterfaceGraphBuilder($labelBuilder);
        $this->digraph = $digraph ?? new Digraph($interfaceElements, $classElements);
    }

    public function name(): string
    {
        return 'Graphviz';
    }

    public function process(Structure $structure): string
    {
        $this->digraph->fromCodeStructure($structure);

        return $this->digraph->toDotLanguage();
    }
}
