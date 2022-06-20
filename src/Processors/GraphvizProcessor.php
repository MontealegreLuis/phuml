<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\EnumDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\TraitDefinition;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EnumGraphBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\TraitGraphBuilder;
use PhUml\Graphviz\Digraph;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Templates\TemplateEngine;

/**
 * It creates a digraph from a `Codebase` and returns it as a string in DOT format
 */
final class GraphvizProcessor implements Processor
{
    public static function fromConfiguration(GraphvizConfiguration $configuration): GraphvizProcessor
    {
        $style = $configuration->digraphStyle();
        $associationsBuilder = $configuration->edgesBuilder();

        return new GraphvizProcessor(
            new ClassGraphBuilder($associationsBuilder),
            new InterfaceGraphBuilder(),
            new TraitGraphBuilder(),
            new EnumGraphBuilder(),
            new DigraphPrinter(new TemplateEngine(), $style)
        );
    }

    private function __construct(
        private readonly ClassGraphBuilder $classBuilder,
        private readonly InterfaceGraphBuilder $interfaceBuilder,
        private readonly TraitGraphBuilder $traitBuilder,
        private readonly EnumGraphBuilder $enumBuilder,
        private readonly DigraphPrinter $printer
    ) {
    }

    public function name(): string
    {
        return 'Graphviz';
    }

    public function process(Codebase $codebase): OutputContent
    {
        $digraph = new Digraph();
        /** @var ClassDefinition|InterfaceDefinition|TraitDefinition|EnumDefinition $definition */
        foreach ($codebase->definitions() as $definition) {
            $this->extractElements($definition, $codebase, $digraph);
        }
        return new OutputContent($this->printer->toDot($digraph));
    }

    private function extractElements(
        ClassDefinition|InterfaceDefinition|TraitDefinition|EnumDefinition $definition,
        Codebase $codebase,
        Digraph $digraph
    ): void {
        match ($definition::class) {
            ClassDefinition::class => $digraph->add($this->classBuilder->extractFrom($definition, $codebase)),
            InterfaceDefinition::class => $digraph->add($this->interfaceBuilder->extractFrom($definition, $codebase)),
            TraitDefinition::class => $digraph->add($this->traitBuilder->extractFrom($definition, $codebase)),
            default => $digraph->add($this->enumBuilder->extractFrom($definition, $codebase)),
        };
    }
}
