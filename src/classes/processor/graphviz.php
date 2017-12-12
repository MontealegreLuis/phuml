<?php

use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

class plGraphvizProcessor extends plProcessor
{
    public $options;

    /** @var plDigraph */
    private $digraph;

    public function __construct(plDigraph $digraph = null)
    {
        $this->options = new plGraphvizProcessorOptions();
        $labelBuilder =  new plNodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../processor/graphviz/digraph/templates')
        ), new plGraphvizProcessorDefaultStyle());
        $classElements = new plClassGraphElements($this->options->createAssociations, $labelBuilder);
        $interfaceElements = new plInterfaceGraphElements($labelBuilder);
        $this->digraph = $digraph ?? new plDigraph($interfaceElements, $classElements);
    }

    public function getInputTypes()
    {
        return [
            'application/phuml-structure'
        ];
    }

    public function getOutputType()
    {
        return 'text/dot';
    }

    public function process($input, $type)
    {
        $this->digraph->fromCodeStructure($input);

        return $this->digraph->toDotLanguage();
    }
}
