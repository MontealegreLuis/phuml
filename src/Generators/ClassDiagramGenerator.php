<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;

/**
 * It generates a UML class diagram from a directory with PHP code
 *
 * The image produced is a `.png` that will be saved in a specified path
 */
final class ClassDiagramGenerator extends DigraphGenerator
{
    private ImageProcessor $imageProcessor;

    public function __construct(
        CodeParser $parser,
        GraphvizProcessor $digraphProcessor,
        ImageProcessor $imageProcessor
    ) {
        parent::__construct($parser, $digraphProcessor);
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * The process to generate a class diagram is as follows
     *
     * 1. The parser produces a collection of classes and interfaces
     * 2. The `graphviz` processor takes this collection and creates a digraph using the DOT language
     * 3. Either the `neato` or `dot` will produce a `.png` class diagram from the digraph
     * 4. The image is saved to the given path
     *
     * @throws LogicException If either the image processor or the command are missing
     */
    public function generate(CodeFinder $finder, string $imagePath): void
    {
        $this->display()->start();
        $image = $this->generateClassDiagram($this->generateDigraph($this->parseCode($finder)));
        $this->save($this->imageProcessor, $image, $imagePath);
    }

    /** @throws LogicException If no command or image processor is provided */
    private function generateClassDiagram(string $digraph): string
    {
        $this->display()->runningProcessor($this->imageProcessor);
        return $this->imageProcessor->process($digraph);
    }
}
