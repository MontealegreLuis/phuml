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
use Webmozart\Assert\Assert;

final class StatisticsInput
{
    private string $directory;

    private string $outputFile;

    private bool $recursive;

    /**
     * @param string[] $arguments
     * @param string[] $options
     */
    public function __construct(array $arguments, array $options)
    {
        $this->directory = $arguments['directory'] ?? '';
        $this->recursive = isset($options['recursive']) && (bool) $options['recursive'];
        $this->setOutputFile($arguments);
    }

    public function outputFile(): string
    {
        return $this->outputFile;
    }

    /** @param string[] $arguments */
    private function setOutputFile(array $arguments): void
    {
        Assert::stringNotEmpty(
            $arguments['output'] ?? '',
            'The output file cannot be empty'
        );
        $this->outputFile = $arguments['output'];
    }

    public function codeFinder(): CodeFinder
    {
        $directory = new CodebaseDirectory($this->directory);
        return $this->recursive ? SourceCodeFinder::recursive($directory) : SourceCodeFinder::nonRecursive($directory);
    }
}
