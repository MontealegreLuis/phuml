<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputContent;

final class CreateClassDiagram
{
    public function __construct(private readonly ImageProcessor $imageProcessor, private readonly ProgressDisplay $display)
    {
    }

    public function __invoke(OutputContent $digraph): OutputContent
    {
        $this->display->runningProcessor($this->imageProcessor);
        return $this->imageProcessor->process($digraph);
    }
}
