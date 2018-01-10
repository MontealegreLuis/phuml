<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use PhUml\Code\Structure;
use PhUml\Graphviz\ClassGraphElements;
use PhUml\Graphviz\Digraph;
use PhUml\Graphviz\HtmlLabelStyle;
use PhUml\Graphviz\InterfaceGraphElements;
use PhUml\Graphviz\NodeLabelBuilder;
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
        $classElements = new ClassGraphElements($createAssociations, $labelBuilder);
        $interfaceElements = new InterfaceGraphElements($labelBuilder);
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
