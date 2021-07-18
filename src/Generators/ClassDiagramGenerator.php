<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use League\Pipeline\Pipeline;
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
use PhUml\Stages\ProgressDisplay;
use PhUml\Stages\SaveFile;

/**
 * It generates a UML class diagram from a directory with PHP code
 *
 * The image produced is a `.png` that will be saved in a specified path
 */
final class ClassDiagramGenerator
{
    public static function fromConfiguration(ClassDiagramConfiguration $configuration): ClassDiagramGenerator
    {
        return new self(
            $configuration->codeFinder(),
            $configuration->codeParser(),
            $configuration->graphvizProcessor(),
            $configuration->imageProcessor(),
            $configuration->writer(),
            $configuration->display()
        );
    }

    public function __construct(
        private CodeFinder $codeFinder,
        private CodeParser $codeParser,
        private GraphvizProcessor $graphvizProcessor,
        private ImageProcessor $imageProcessor,
        private OutputWriter $writer,
        private ProgressDisplay $display
    ) {
    }

    public function generate(GeneratorInput $input): void
    {
        $pipeline = (new Pipeline())
            ->pipe(new FindCode($this->codeFinder, $this->display))
            ->pipe(new ParseCode($this->codeParser, $this->display))
            ->pipe(new CreateDigraph($this->graphvizProcessor, $this->display))
            ->pipe(new CreateClassDiagram($this->imageProcessor, $this->display))
            ->pipe(new SaveFile($this->writer, $input->outputFile(), $this->display));

        $pipeline->process($input->directory());
    }
}
