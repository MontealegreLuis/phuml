<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use plProcessorOptions;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

abstract class ImageProcessor extends Processor
{
    /** @var plProcessorOptions */
    public $options;

    /** @var Process */
    protected $process;

    /** @var Filesystem */
    private $fileSystem;

    public function __construct(Process $process = null, Filesystem $fileSystem = null)
    {
        $this->options = new plProcessorOptions();
        $this->process = $process ?? new Process($this->command());
        $this->fileSystem = $fileSystem ?? new Filesystem();
    }

    public function getInputType(): string
    {
        return 'text/dot';
    }

    public function getOutputType(): string
    {
        return 'image/png';
    }

    public function process($input)
    {
        $dotFile = $this->fileSystem->tempnam('/tmp', 'phuml');
        $imageFile = $this->fileSystem->tempnam('/tmp', 'phuml');

        $this->fileSystem->dumpFile($dotFile, $input);
        $this->fileSystem->remove($imageFile);

        $this->execute($dotFile, $imageFile);

        $image = file_get_contents($imageFile);

        $this->fileSystem->remove($dotFile);
        $this->fileSystem->remove($imageFile);

        return $image;
    }

    public function execute(string $inputFile, string $outputFile): void
    {
        $this->process->setCommandLine([$this->command(), '-Tpng', '-o', $outputFile, $inputFile]);
        $this->process->run();
        if (!$this->process->isSuccessful()) {
            throw new ImageGenerationFailure($this->process->getErrorOutput());
        }
    }

    abstract public function command(): string;
}
