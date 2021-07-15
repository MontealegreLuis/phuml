<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Console\Commands\GeneratorInput;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\OutputWriter;
use PhUml\Processors\StatisticsProcessor;
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
            $configuration->sourceCodeFinder(),
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
        $input->display()->start();
        $sourceCode = $this->codeFinder->find($input->directory());
        $input->display()->runningParser();
        $codebase = $this->codeParser->parse($sourceCode);
        $input->display()->runningProcessor($this->statisticsProcessor);
        $statistics = $this->statisticsProcessor->process($codebase);
        $input->display()->savingResult();
        $this->writer->save($statistics, $input->outputFile());
    }
}
