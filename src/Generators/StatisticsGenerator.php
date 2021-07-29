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
use PhUml\Stages\CalculateStatistics;
use PhUml\Stages\FindCode;
use PhUml\Stages\ParseCode;
use PhUml\Stages\SaveFile;

/**
 * It generates a text file with the statistics of an object oriented codebase
 *
 * It reports the number of classes and interfaces, number of private, public and protected methods
 * among other details
 */
final class StatisticsGenerator
{
    public static function fromConfiguration(StatisticsGeneratorConfiguration $configuration): StatisticsGenerator
    {
        return new StatisticsGenerator(
            new FindCode($configuration->codeFinder(), $configuration->display()),
            new ParseCode($configuration->codeParser(), $configuration->display()),
            new CalculateStatistics($configuration->statisticsProcessor(), $configuration->display()),
            new SaveFile($configuration->writer(), $configuration->display()),
        );
    }

    private function __construct(
        private FindCode $findCode,
        private ParseCode $parseCode,
        private CalculateStatistics $calculateStatistics,
        private SaveFile $saveFile,
    ) {
    }

    public function generate(GeneratorInput $input): void
    {
        $pipeline = (new Pipeline())
            ->pipe($this->findCode)
            ->pipe($this->parseCode)
            ->pipe($this->calculateStatistics)
            ->pipe(fn (OutputContent $content) => $this->saveFile->saveTo($content, $input->filePath()));

        $pipeline->process($input->codebaseDirectory());
    }
}
