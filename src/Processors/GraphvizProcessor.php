<?php declare(strict_types=1);
/**
 * PHP version 7.2
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

/**
 * It creates a digraph from a `Structure` and returns it as a string in DOT format
 */
final class GraphvizProcessor extends Processor
{
    /** @var ClassGraphBuilder */
    private $classBuilder;

    /** @var InterfaceGraphBuilder */
    private $interfaceBuilder;

    /** @var DigraphPrinter */
    private $printer;

    /** @var TraitGraphBuilder */
    private $traitBuilder;

    public function __construct(
        ClassGraphBuilder $classBuilder = null,
        InterfaceGraphBuilder $interfaceBuilder = null,
        TraitGraphBuilder $traitBuilder = null,
        DigraphPrinter $printer = null
    ) {
        $this->classBuilder = $classBuilder ?? new ClassGraphBuilder();
        $this->interfaceBuilder = $interfaceBuilder ?? new InterfaceGraphBuilder();
        $this->traitBuilder = $traitBuilder ?? new TraitGraphBuilder();
        $this->printer = $printer ?? new DigraphPrinter();
    }

    public function name(): string
    {
        return 'Graphviz';
    }

    public function process(Codebase $codebase): string
    {
        $digraph = new Digraph();
        foreach ($codebase->definitions() as $definition) {
            $this->extractElements($definition, $codebase, $digraph);
        }
        return $this->printer->toDot($digraph);
    }

    protected function extractElements(
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
