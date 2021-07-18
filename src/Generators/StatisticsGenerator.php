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
use PhUml\Processors\OutputWriter;
use PhUml\Processors\StatisticsProcessor;
use PhUml\Stages\CalculateStatistics;
use PhUml\Stages\FindCode;
use PhUml\Stages\ParseCode;
use PhUml\Stages\ProgressDisplay;
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
            $configuration->codeFinder(),
            $configuration->codeParser(),
            $configuration->statisticsProcessor(),
            $configuration->writer(),
            $configuration->display()
        );
    }

    private function __construct(
        private CodeFinder $codeFinder,
        private CodeParser $codeParser,
        private StatisticsProcessor $statisticsProcessor,
        private OutputWriter $writer,
        private ProgressDisplay $display
    ) {
    }

    public function generate(GeneratorInput $input): void
    {
        $pipeline = (new Pipeline())
            ->pipe(new FindCode($this->codeFinder, $this->display))
            ->pipe(new ParseCode($this->codeParser, $this->display))
            ->pipe(new CalculateStatistics($this->statisticsProcessor, $this->display))
            ->pipe(new SaveFile($this->writer, $input->outputFile(), $this->display));

        $pipeline->process($input->directory());
    }
}
