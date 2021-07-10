<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Code\Codebase;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputContent;

class DigraphGenerator extends Generator
{
    protected GraphvizProcessor $digraphProcessor;

    public function __construct(GraphvizProcessor $digraphProcessor)
    {
        $this->digraphProcessor = $digraphProcessor;
    }

    protected function generateDigraph(Codebase $codebase, ProgressDisplay $display): OutputContent
    {
        $display->runningProcessor($this->digraphProcessor);
        return $this->digraphProcessor->process($codebase);
    }
}
