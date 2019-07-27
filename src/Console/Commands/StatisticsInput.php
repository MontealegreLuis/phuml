<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Webmozart\Assert\Assert;

class StatisticsInput
{
    /** @var string */
    private $directory;

    /** @var string */
    private $outputFile;

    /** @var bool */
    private $recursive;

    public function __construct(array $arguments, array $options)
    {
        $this->setDirectory($arguments);
        $this->setOutputFile($arguments);
        $this->setRecursive($options);
    }

    public function directory(): string
    {
        return $this->directory;
    }

    public function outputFile(): string
    {
        return $this->outputFile;
    }

    public function recursive(): bool
    {
        return $this->recursive;
    }

    private function setDirectory(array $arguments): void
    {
        Assert::stringNotEmpty(
            $arguments['directory'] ?? '',
            'The directory with the code to be scanned cannot be empty'
        );
        $this->directory = $arguments['directory'];
    }

    private function setOutputFile(array $arguments): void
    {
        Assert::stringNotEmpty(
            $arguments['output'] ?? '',
            'The output file cannot be empty'
        );
        $this->outputFile = $arguments['output'];
    }

    private function setRecursive(array $options): void
    {
        $this->recursive = isset($options['recursive']) ? (bool)$options['recursive']  : false;
    }
}
