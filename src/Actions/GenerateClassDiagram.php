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

class GenerateClassDiagram extends Action
{
    /** @var TokenParser */
    private $parser;

    /** @var GraphvizProcessor */
    private $dotProcessor;

    /** @var ImageProcessor */
    private $imageProcessor;

    public function __construct(TokenParser $parser, GraphvizProcessor $dotProcessor) {
        $this->parser = $parser;
        $this->dotProcessor = $dotProcessor;
    }

    public function setImageProcessor(ImageProcessor $imageProcessor): void
    {
        $this->imageProcessor = $imageProcessor;
    }

    /**
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

    /** @throws LogicException */
    private function imageProcessor(): ImageProcessor
    {
        if (!$this->imageProcessor) {
            throw new LogicException('No image processor was provided');
        }
        return $this->imageProcessor;
    }
}
