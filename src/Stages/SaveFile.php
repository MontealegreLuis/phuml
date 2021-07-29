<?php declare(strict_types=1);
/**
 * PHP version 8.0
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
        private OutputWriter $writer,
        private ProgressDisplay $display
    ) {
    }

    public function saveTo(OutputContent $content, OutputFilePath $path): void
    {
        $this->display->savingResult();
        $this->writer->save($content, $path);
    }
}
