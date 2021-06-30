<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Webmozart\Assert\Assert;

final class StatisticsInput
{
    /** @var string */
    private $directory;

    /** @var string */
    private $outputFile;

    /** @var bool */
    private $recursive;

    /**
     * @param string[] $arguments
     * @param string[] $options
     */
    public function __construct(array $arguments, array $options)
    {
        $this->directory = $arguments['directory'] ?? '';
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

    /** @param string[] $arguments */
    private function setOutputFile(array $arguments): void
    {
        Assert::stringNotEmpty(
            $arguments['output'] ?? '',
            'The output file cannot be empty'
        );
        $this->outputFile = $arguments['output'];
    }

    /** @param string[] $options */
    private function setRecursive(array $options): void
    {
        $this->recursive = isset($options['recursive']) && (bool) $options['recursive'];
    }
}
