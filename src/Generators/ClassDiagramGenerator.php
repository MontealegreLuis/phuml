<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Code\Codebase;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputContent;

/**
 * It generates a UML class diagram from a directory with PHP code
 *
 * The image produced is a `.png` that will be saved in a specified path
 */
final class ClassDiagramGenerator extends DigraphGenerator
{
    private ImageProcessor $imageProcessor;

    public function __construct(GraphvizProcessor $digraphProcessor, ImageProcessor $imageProcessor)
    {
        parent::__construct($digraphProcessor);
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * The process to generate a class diagram is as follows
     *
     * 1. The parser produces a collection of classes, interfaces and traits
     * 2. The `graphviz` processor takes this collection and creates a digraph using the DOT language
     * 3. Either the `neato` or `dot` processor will produce a `.png` class diagram from the digraph
     * 4. The image is saved to the given path
     */
    public function generate(Codebase $codebase, ProgressDisplay $display): OutputContent
    {
        $digraph = $this->generateDigraph($codebase, $display);
        $display->runningProcessor($this->imageProcessor);
        return $this->imageProcessor->process($digraph);
    }
}
