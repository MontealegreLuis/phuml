<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\OutputFilePath;

final class StatisticsInput
{
    private CodebaseDirectory $directory;

    private OutputFilePath $outputFile;

    private bool $recursive;

    /**
     * @param string[] $arguments
     * @param string[] $options
     */
    public function __construct(array $arguments, array $options)
    {
        $this->directory = new CodebaseDirectory($arguments['directory'] ?? '');
        $this->recursive = isset($options['recursive']) && (bool) $options['recursive'];
        $this->outputFile = new OutputFilePath($arguments['output'] ?? '');
    }

    public function outputFile(): OutputFilePath
    {
        return $this->outputFile;
    }

    public function codeFinder(): CodeFinder
    {
        return $this->recursive
            ? SourceCodeFinder::recursive($this->directory)
            : SourceCodeFinder::nonRecursive($this->directory);
    }
}
