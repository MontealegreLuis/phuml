<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

abstract class ImageProcessor extends Processor
{
    /** @var Process */
    protected $process;

    /** @var Filesystem */
    private $fileSystem;

    public function __construct(Process $process = null, Filesystem $fileSystem = null)
    {
        $this->process = $process ?? new Process($this->command());
        $this->fileSystem = $fileSystem ?? new Filesystem();
    }

    public function process(string $digraphInDotFormat): string
    {
        $dotFile = $this->fileSystem->tempnam('/tmp', 'phuml');
        $imageFile = $this->fileSystem->tempnam('/tmp', 'phuml');

        $this->fileSystem->dumpFile($dotFile, $digraphInDotFormat);
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
