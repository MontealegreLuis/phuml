<?php

use PhUml\Graphviz\ClassGraphElements;
use PhUml\Graphviz\Digraph;
use PhUml\Graphviz\InterfaceGraphElements;
use PhUml\Graphviz\NodeLabelBuilder;
use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

class plGraphvizProcessor extends plProcessor
{
    public $options;

    /** @var Digraph */
    private $digraph;

    public function __construct(Digraph $digraph = null)
    {
        $this->options = new plGraphvizProcessorOptions();
        $labelBuilder =  new NodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../../Graphviz/templates')
        ), new plGraphvizProcessorStyle());
        $classElements = new ClassGraphElements($this->options->createAssociations, $labelBuilder);
        $interfaceElements = new InterfaceGraphElements($labelBuilder);
        $this->digraph = $digraph ?? new Digraph($interfaceElements, $classElements);
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
