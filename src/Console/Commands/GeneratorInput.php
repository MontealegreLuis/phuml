<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Generators\ProgressDisplay;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Processors\OutputFilePath;

final class GeneratorInput
{
    private CodebaseDirectory $directory;

    private OutputFilePath $outputFile;

    private ProgressDisplay $display;

    /** @param string[] $input */
    public function __construct(array $input, ProgressDisplay $display)
    {
        $this->directory = new CodebaseDirectory($input['directory'] ?? '');
        $this->outputFile = new OutputFilePath($input['output'] ?? '');
        $this->display = $display;
    }

    public function outputFile(): OutputFilePath
    {
        return $this->outputFile;
    }

    public function directory(): CodebaseDirectory
    {
        return $this->directory;
    }

    public function display(): ProgressDisplay
    {
        return $this->display;
    }
}
