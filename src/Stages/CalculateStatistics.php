<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Code\Codebase;
use PhUml\Processors\OutputContent;
use PhUml\Processors\StatisticsProcessor;

final class CalculateStatistics
{
    public function __construct(private readonly StatisticsProcessor $statisticsProcessor, private readonly ProgressDisplay $display)
    {
    }

    public function __invoke(Codebase $codebase): OutputContent
    {
        $this->display->runningProcessor($this->statisticsProcessor);
        return $this->statisticsProcessor->process($codebase);
    }
}
