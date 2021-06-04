<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Webmozart\Assert\Assert;

final class GeneratorInput
{
    /** @var string */
    private $directory;

    /** @var string */
    private $outputFile;

    /** @var mixed[] $options */
    private $options;

    /**
     * @param string[] $arguments
     * @param mixed[] $options
     */
    public function __construct(array $arguments, array $options)
    {
        $this->setDirectory($arguments);
        $this->setOutputFile($arguments);
        $this->options = $options;
    }

    public function directory(): string
    {
        return $this->directory;
    }

    public function outputFile(): string
    {
        return $this->outputFile;
    }

    /** @return mixed[] $options */
    public function options(): array
    {
        return $this->options;
    }

    /** @param string[] $arguments */
    private function setDirectory(array $arguments): void
    {
        Assert::stringNotEmpty(
            $arguments['directory'] ?? '',
            'The directory with the code to be scanned cannot be empty'
        );
        $this->directory = $arguments['directory'];
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
}
