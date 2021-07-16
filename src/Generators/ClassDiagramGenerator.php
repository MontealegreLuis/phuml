<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use League\Pipeline\Pipeline;
use PhUml\Configuration\ClassDiagramConfiguration;
use PhUml\Console\Commands\GeneratorInput;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputWriter;
use PhUml\Stages\CreateClassDiagram;
use PhUml\Stages\CreateDigraph;
use PhUml\Stages\FindCode;
use PhUml\Stages\ParseCode;
use PhUml\Stages\SaveFile;

/**
 * It generates a UML class diagram from a directory with PHP code
 *
 * The image produced is a `.png` that will be saved in a specified path
 */
final class ClassDiagramGenerator
{
    private CodeFinder $codeFinder;

    private CodeParser $codeParser;

    private GraphvizProcessor $graphvizProcessor;

    private ImageProcessor $imageProcessor;

    private OutputWriter $writer;

    public static function fromConfiguration(ClassDiagramConfiguration $configuration): ClassDiagramGenerator
    {
        return new self(
            $configuration->codeFinder(),
            $configuration->codeParser(),
            $configuration->graphvizProcessor(),
            $configuration->imageProcessor(),
            $configuration->writer()
        );
    }

    public function __construct(
        CodeFinder $codeFinder,
        CodeParser $codeParser,
        GraphvizProcessor $graphvizProcessor,
        ImageProcessor $imageProcessor,
        OutputWriter $writer
    ) {
        $this->codeFinder = $codeFinder;
        $this->codeParser = $codeParser;
        $this->graphvizProcessor = $graphvizProcessor;
        $this->imageProcessor = $imageProcessor;
        $this->writer = $writer;
    }

    /**
     * The process to generate a class diagram is as follows
     *
     * 1. The parser produces a collection of classes, interfaces and traits
     * 2. The `graphviz` processor takes this collection and creates a digraph using the DOT language
     * 3. Either the `neato` or `dot` processor will produce a `.png` class diagram from the digraph
     * 4. The image is saved to the given path
     */
    public function generate(GeneratorInput $input): void
    {
        $pipeline = (new Pipeline())
            ->pipe(new FindCode($this->codeFinder, $input->display()))
            ->pipe(new ParseCode($this->codeParser, $input->display()))
            ->pipe(new CreateDigraph($this->graphvizProcessor, $input->display()))
            ->pipe(new CreateClassDiagram($this->imageProcessor, $input->display()))
            ->pipe(new SaveFile($this->writer, $input->outputFile(), $input->display()));

        $pipeline->process($input->directory());
    }
}
