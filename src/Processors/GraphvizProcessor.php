<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Structure;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Digraph;
use PhUml\Graphviz\DigraphPrinter;

/**
 * It creates a digraph from a `Structure` and returns it as a string in DOT format
 */
class GraphvizProcessor extends Processor
{
    /** @var ClassGraphBuilder */
    private $classBuilder;

    /** @var InterfaceGraphBuilder */
    private $interfaceBuilder;

    /** @var DigraphPrinter */
    private $printer;

    public function __construct(
        ClassGraphBuilder $classBuilder = null,
        InterfaceGraphBuilder $interfaceBuilder = null,
        DigraphPrinter $printer = null
    ) {
        $this->classBuilder = $classBuilder ?? new ClassGraphBuilder();
        $this->interfaceBuilder = $interfaceBuilder ?? new InterfaceGraphBuilder();
        $this->printer = $printer ?? new DigraphPrinter();
    }

    public function name(): string
    {
        return 'Graphviz';
    }

    public function process(Structure $structure): string
    {
        $digraph = new Digraph();
        foreach ($structure->definitions() as $definition) {
            $this->extractElements($definition, $structure, $digraph);
        }
        return $this->printer->toDot($digraph);
    }

    protected function extractElements(
        Definition $definition,
        Structure $structure,
        Digraph $digraph
    ): void
    {
        if ($definition instanceof ClassDefinition) {
            $digraph->add($this->classBuilder->extractFrom($definition, $structure));
        } elseif ($definition instanceof InterfaceDefinition) {
            $digraph->add($this->interfaceBuilder->extractFrom($definition));
        }
    }
}
