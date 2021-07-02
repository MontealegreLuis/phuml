<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * It generates a `png` class diagram from a digraph in DOT format
 *
 * It takes a digraph in DOT format, saves it to a temporary location and creates a `png` class
 * diagram out of it.
 * It uses either the `dot` or `neato` command to create the image
 */
abstract class ImageProcessor extends Processor
{
    /** @var Process<string>|null */
    protected $process;

    private Filesystem $fileSystem;

    /** @param Process<string> $process */
    public function __construct(Process $process = null, Filesystem $fileSystem = null)
    {
        $this->process = $process;
        $this->fileSystem = $fileSystem ?? new Filesystem();
    }

    /**
     * It returns the contents of a `png` class diagram
     */
    public function process(string $digraphInDotFormat): string
    {
        $dotFile = $this->fileSystem->tempnam('/tmp', 'phuml');
        $imageFile = $this->fileSystem->tempnam('/tmp', 'phuml');

        $this->fileSystem->dumpFile($dotFile, $digraphInDotFormat);
        $this->fileSystem->remove($imageFile);

        $this->execute($dotFile, $imageFile);

        $image = (string) file_get_contents($imageFile);

        $this->fileSystem->remove($dotFile);
        $this->fileSystem->remove($imageFile);

        return $image;
    }

    /**
     * @throws ImageGenerationFailure If the Grpahviz command failed
     */
    public function execute(string $inputFile, string $outputFile): void
    {
        $process = $this->process ?? new Process([$this->command(), '-Tpng', '-o', $outputFile, $inputFile]);
        $process->run();
        if (! $process->isSuccessful()) {
            throw ImageGenerationFailure::withOutput($process->getErrorOutput());
        }
    }

    abstract public function command(): string;
}
