<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Processors\OutputContent;
use PhUml\Processors\OutputFilePath;
use PhUml\Processors\OutputWriter;

final class SaveFile
{
    private OutputWriter $writer;

    private OutputFilePath $path;

    private ProgressDisplay $display;

    public function __construct(OutputWriter $writer, OutputFilePath $path, ProgressDisplay $display)
    {
        $this->writer = $writer;
        $this->path = $path;
        $this->display = $display;
    }

    public function __invoke(OutputContent $content): void
    {
        $this->display->savingResult();
        $this->writer->save($content, $this->path);
    }
}
