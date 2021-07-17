<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Code\Codebase;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputContent;

final class CreateDigraph
{
    private GraphvizProcessor $processor;

    private ProgressDisplay $display;

    public function __construct(GraphvizProcessor $processor, ProgressDisplay $display)
    {
        $this->processor = $processor;
        $this->display = $display;
    }

    public function __invoke(Codebase $codebase): OutputContent
    {
        $this->display->runningProcessor($this->processor);
        return $this->processor->process($codebase);
    }
}
