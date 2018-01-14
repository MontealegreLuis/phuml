<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use LogicException;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;

/**
 * It generates a UML class diagram from a directory with PHP code
 *
 * The image produced is a `.png` that will be saved in a specified path
 */
class GenerateClassDiagram extends Action
{
    /** @var TokenParser */
    private $parser;

    /** @var GraphvizProcessor */
    private $dotProcessor;

    /** @var ImageProcessor */
    private $imageProcessor;

    public function __construct(TokenParser $parser, GraphvizProcessor $dotProcessor)
    {
        $this->parser = $parser;
        $this->dotProcessor = $dotProcessor;
    }

    public function setImageProcessor(ImageProcessor $imageProcessor): void
    {
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
        $this->command()->runningParser();
        $structure = $this->parser->parse($finder);
        $this->command()->runningProcessor($this->dotProcessor);
        $dotLanguage = $this->dotProcessor->process($structure);
        $this->command()->runningProcessor($this->imageProcessor());
        $image = $this->imageProcessor()->process($dotLanguage);
        $this->command()->savingResult();
        $this->imageProcessor->writeToDisk($image, $imagePath);
    }

    /** @throws LogicException If no image processor is provided */
    private function imageProcessor(): ImageProcessor
    {
        if (!$this->imageProcessor) {
            throw new LogicException('No image processor was provided');
        }
        return $this->imageProcessor;
    }
}
