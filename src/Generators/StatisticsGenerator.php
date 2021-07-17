<?php declare(strict_types=1);
/**
 * PHP version 7.4
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
use PhUml\Stages\SaveFile;
use PhUml\Templates\TemplateFailure;

/**
 * It generates a text file with the statistics of an object oriented codebase
 *
 * It reports the number of classes and interfaces, number of private, public and protected methods
 * among other details
 */
final class StatisticsGenerator
{
    private CodeFinder $codeFinder;

    private CodeParser $codeParser;

    private StatisticsProcessor $statisticsProcessor;

    private OutputWriter $writer;

    public static function fromConfiguration(StatisticsGeneratorConfiguration $configuration): StatisticsGenerator
    {
        return new StatisticsGenerator(
            $configuration->codeFinder(),
            $configuration->codeParser(),
            $configuration->statisticsProcessor(),
            $configuration->writer()
        );
    }

    private function __construct(
        CodeFinder $codeFinder,
        CodeParser $codeParser,
        StatisticsProcessor $statisticsProcessor,
        OutputWriter $writer
    ) {
        $this->codeFinder = $codeFinder;
        $this->codeParser = $codeParser;
        $this->statisticsProcessor = $statisticsProcessor;
        $this->writer = $writer;
    }

    /**
     * It generates a text file with statistics
     *
     * @throws TemplateFailure If Twig fails
     */
    public function generate(GeneratorInput $input): void
    {
        $pipeline = (new Pipeline())
            ->pipe(new FindCode($this->codeFinder, $input->display()))
            ->pipe(new ParseCode($this->codeParser, $input->display()))
            ->pipe(new CalculateStatistics($this->statisticsProcessor, $input->display()))
            ->pipe(new SaveFile($this->writer, $input->outputFile(), $input->display()));

        $pipeline->process($input->directory());
    }
}
