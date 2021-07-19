<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Parser\CodebaseDirectory;
use PhUml\Processors\OutputFilePath;

final class GeneratorInput
{
    private CodebaseDirectory $directory;

    private OutputFilePath $outputFile;

    /** @param string[] $input */
    public function __construct(array $input)
    {
        $this->directory = new CodebaseDirectory($input['directory'] ?? '');
        $this->outputFile = new OutputFilePath($input['output'] ?? '');
    }

    public function filePath(): OutputFilePath
    {
        return $this->outputFile;
    }

    public function codebaseDirectory(): CodebaseDirectory
    {
        return $this->directory;
    }
}
