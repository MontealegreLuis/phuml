<?php declare(strict_types=1);
/**
 * PHP version 7.4
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

    /** @var mixed[] $options */
    private array $options;

    /**
     * @param string[] $arguments
     * @param mixed[] $options
     */
    public function __construct(array $arguments, array $options)
    {
        $this->directory = new CodebaseDirectory($arguments['directory'] ?? '');
        $this->outputFile = new OutputFilePath($arguments['output'] ?? '');
        $this->options = $options;
    }

    public function directory(): CodebaseDirectory
    {
        return $this->directory;
    }

    public function outputFile(): OutputFilePath
    {
        return $this->outputFile;
    }

    /** @return mixed[] $options */
    public function options(): array
    {
        return $this->options;
    }
}
