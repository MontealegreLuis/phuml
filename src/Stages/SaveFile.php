<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PhUml\Processors\OutputContent;
use PhUml\Processors\OutputFilePath;
use PhUml\Processors\OutputWriter;

final class SaveFile
{
    public function __construct(
        private readonly OutputWriter $writer,
        private readonly ProgressDisplay $display
    ) {
    }

    public function saveTo(OutputContent $content, OutputFilePath $path): void
    {
        $this->display->savingResult();
        $this->writer->save($content, $path);
    }
}
