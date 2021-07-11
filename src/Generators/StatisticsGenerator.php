<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Code\Codebase;
use PhUml\Processors\OutputContent;
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
    private StatisticsProcessor $statisticsProcessor;

    public function __construct(StatisticsProcessor $statisticsProcessor)
    {
        $this->statisticsProcessor = $statisticsProcessor;
    }

    /**
     * The process to generate a text file with statistics is as follows
     *
     * 1. The parser produces a collection of classes and interfaces
     * 2. The `statistics` processor takes this collection and creates a summary
     * 4. The text file with the statistics is saved to the given path
     *
     * @throws TemplateFailure If Twig fails
     */
    public function generate(Codebase $codebase, ProgressDisplay $display): OutputContent
    {
        $display->runningProcessor($this->statisticsProcessor);
        return $this->statisticsProcessor->process($codebase);
    }
}
