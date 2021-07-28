<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use League\Pipeline\Pipeline;
use PhUml\Console\Commands\GeneratorInput;
use PhUml\Processors\OutputContent;
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
    public static function fromConfiguration(ClassDiagramConfiguration $configuration): ClassDiagramGenerator
    {
        return new self(
            new FindCode($configuration->codeFinder(), $configuration->display()),
            new ParseCode($configuration->codeParser(), $configuration->display()),
            new CreateDigraph($configuration->graphvizProcessor(), $configuration->display()),
            new CreateClassDiagram($configuration->imageProcessor(), $configuration->display()),
            new SaveFile($configuration->writer(), $configuration->display()),
        );
    }

    private function __construct(
        private FindCode $findCode,
        private ParseCode $parseCode,
        private CreateDigraph $createDigraph,
        private CreateClassDiagram $createClassDiagram,
        private SaveFile $saveFile,
    ) {
    }

    public function generate(GeneratorInput $input): void
    {
        $pipeline = (new Pipeline())
            ->pipe($this->findCode)
            ->pipe($this->parseCode)
            ->pipe($this->createDigraph)
            ->pipe($this->createClassDiagram)
            ->pipe(fn (OutputContent $content) => $this->saveFile->saveTo($content, $input->filePath()));

        $pipeline->process($input->codebaseDirectory());
    }
}
