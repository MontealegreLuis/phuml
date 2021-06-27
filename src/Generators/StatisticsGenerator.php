<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Code\Codebase;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\StatisticsProcessor;

/**
 * It generates a text file with the statistics of an object oriented codebase
 *
 * It reports the number of classes and interfaces, number of private, public and protected methods
 * among other details
 */
final class StatisticsGenerator extends Generator
{
    /** @var StatisticsProcessor */
    private $statisticsProcessor;

    public function __construct(CodeParser $parser, StatisticsProcessor $statisticsProcessor)
    {
        parent::__construct($parser);
        $this->statisticsProcessor = $statisticsProcessor;
    }

    /**
     * The process to generate a text file with statistics is as follows
     *
     * 1. The parser produces a collection of classes and interfaces
     * 2. The `statistics` processor takes this collection and creates a summary
     * 4. The text file with the statistics is saved to the given path
     *
     * @throws \PhUml\Templates\TemplateFailure If Twig fails
     * @throws \LogicException If the command is missing
     */
    public function generate(CodeFinder $finder, string $statisticsFilePath): void
    {
        $this->display()->start();
        $statistics = $this->generateStatistics($this->parseCode($finder));
        $this->save($this->statisticsProcessor, $statistics, $statisticsFilePath);
    }

    private function generateStatistics(Codebase $codebase): string
    {
        $this->display()->runningProcessor($this->statisticsProcessor);
        return $this->statisticsProcessor->process($codebase);
    }
}
