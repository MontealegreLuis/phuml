<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Actions;

use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\StatisticsProcessor;

class GenerateStatistics extends Action
{
    /** @var TokenParser */
    private $parser;

    /** @var StatisticsProcessor */
    private $processor;

    public function __construct(TokenParser $parser, StatisticsProcessor $processor)
    {
        $this->parser = $parser;
        $this->processor = $processor;
    }

    /** @throws \LogicException If the command is missing */
    public function generate(CodeFinder $finder, string $filePath): void
    {
        $this->command()->runningParser();
        $structure = $this->parser->parse($finder);
        $this->command()->runningProcessor($this->processor);
        $statistics = $this->processor->process($structure);
        $this->command()->savingResult();
        $this->processor->writeToDisk($statistics, $filePath);
    }
}
