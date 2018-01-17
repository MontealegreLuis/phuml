<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\StatisticsProcessor;

/**
 * It generates a text file with the statistics of an object oriented codebase
 *
 * It reports the number of classes and interfaces, number of private, public and protected methods
 * among other details
 */
class GenerateStatistics extends Action
{
    /** @var CodeParser */
    private $parser;

    /** @var StatisticsProcessor */
    private $processor;

    public function __construct(CodeParser $parser, StatisticsProcessor $processor)
    {
        $this->parser = $parser;
        $this->processor = $processor;
    }

    /**
     * The process to generate a text file with statistics is as follows
     *
     * 1. The parser produces a collection of classes and interfaces
     * 2. The `statistics` processor takes this collection and creates a summary
     * 4. The text file with the statistics is saved to the given path
     *
     * @throws \LogicException If the command is missing
     */
    public function generate(CodeFinder $finder, string $filePath): void
    {
        $this->command()->runningParser();
        $structure = $this->parser->parse($finder);
        $this->command()->runningProcessor($this->processor);
        $statistics = $this->processor->process($structure);
        $this->command()->savingResult();
        $this->processor->saveToFile($statistics, $filePath);
    }
}
