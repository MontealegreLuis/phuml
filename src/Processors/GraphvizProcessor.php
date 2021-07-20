<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\TraitDefinition;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\TraitGraphBuilder;
use PhUml\Graphviz\Digraph;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Templates\TemplateEngine;

/**
 * It creates a digraph from a `Structure` and returns it as a string in DOT format
 */
final class GraphvizProcessor implements Processor
{
    public static function fromConfiguration(GraphvizConfiguration $configuration): GraphvizProcessor
    {
        $style = $configuration->digraphStyle();
        $associationsBuilder = $configuration->associationsBuilder();

        return new GraphvizProcessor(
            new ClassGraphBuilder($associationsBuilder),
            new InterfaceGraphBuilder(),
            new TraitGraphBuilder(),
            new DigraphPrinter(new TemplateEngine(), $style)
        );
    }

    private function __construct(
        private ClassGraphBuilder $classBuilder,
        private InterfaceGraphBuilder $interfaceBuilder,
        private TraitGraphBuilder $traitBuilder,
        private DigraphPrinter $printer
    ) {
    }

    public function name(): string
    {
        return 'Graphviz';
    }

    public function process(Codebase $codebase): OutputContent
    {
        $digraph = new Digraph();
        foreach ($codebase->definitions() as $definition) {
            $this->extractElements($definition, $codebase, $digraph);
        }
        return new OutputContent($this->printer->toDot($digraph));
    }

    private function extractElements(
        Definition $definition,
        Codebase $codebase,
        Digraph $digraph
    ): void {
        if ($definition instanceof ClassDefinition) {
            $digraph->add($this->classBuilder->extractFrom($definition, $codebase));
        } elseif ($definition instanceof InterfaceDefinition) {
            $digraph->add($this->interfaceBuilder->extractFrom($definition, $codebase));
        } elseif ($definition instanceof TraitDefinition) {
            $digraph->add($this->traitBuilder->extractFrom($definition, $codebase));
        }
    }
}
