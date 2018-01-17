<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Structure;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\HtmlLabelStyle;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Graphviz\Digraph;
use PhUml\Templates\TemplateEngine;

/**
 * It creates a digraph from a `Structure` and returns it as a string in DOT format
 */
class GraphvizProcessor extends Processor
{
    /** @var ClassGraphBuilder */
    private $classBuilder;

    /** @var InterfaceGraphBuilder */
    private $interfaceBuilder;

    public function __construct(
        ClassGraphBuilder $classBuilder = null,
        InterfaceGraphBuilder $interfaceBuilder = null
    ) {
        $labelBuilder = new NodeLabelBuilder(new TemplateEngine(), new HtmlLabelStyle());
        $this->classBuilder = $classBuilder ?? new ClassGraphBuilder($labelBuilder);
        $this->interfaceBuilder = $interfaceBuilder ?? new InterfaceGraphBuilder($labelBuilder);
    }

    public function createAssociations(): void
    {
        $this->classBuilder->createAssociations();
    }

    public function name(): string
    {
        return 'Graphviz';
    }

    public function process(Structure $structure): string
    {
        $digraph = new Digraph();
        foreach ($structure->definitions() as $definition) {
            if ($definition instanceof ClassDefinition) {
                $digraph->add($this->classBuilder->extractFrom($definition, $structure));
            } elseif ($definition instanceof InterfaceDefinition) {
                $digraph->add($this->interfaceBuilder->extractFrom($definition));
            }
        }
        return $digraph->toDotLanguage();
    }
}
