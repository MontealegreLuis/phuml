<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputContent;

final class CreateClassDiagram
{
    private ImageProcessor $imageProcessor;

    private ProgressDisplay $display;

    public function __construct(ImageProcessor $imageProcessor, ProgressDisplay $display)
    {
        $this->imageProcessor = $imageProcessor;
        $this->display = $display;
    }

    public function __invoke(OutputContent $digraph): OutputContent
    {
        $this->display->runningProcessor($this->imageProcessor);
        return $this->imageProcessor->process($digraph);
    }
}
