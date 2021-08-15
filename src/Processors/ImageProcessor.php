<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use Symfony\Component\Process\Process;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * It generates a `png` class diagram from a digraph in DOT format
 *
 * It takes a digraph in DOT format, saves it to a temporary location and creates a `png` class
 * diagram out of it.
 * It uses either the `dot` or `neato` command to create the image
 */
final class ImageProcessor implements Processor
{
    public static function neato(SmartFileSystem $filesystem): ImageProcessor
    {
        return new ImageProcessor(new ImageProcessorName('neato'), $filesystem);
    }

    public static function dot(SmartFileSystem $filesystem): ImageProcessor
    {
        return new ImageProcessor(new ImageProcessorName('dot'), $filesystem);
    }

    private function __construct(private ImageProcessorName $name, private SmartFileSystem $fileSystem)
    {
    }

    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * It returns the contents of a `png` class diagram
     */
    public function process(OutputContent $digraphInDotFormat): OutputContent
    {
        $dotFile = $this->fileSystem->tempnam(sys_get_temp_dir(), 'phuml');
        $imageFile = $this->fileSystem->tempnam(sys_get_temp_dir(), 'phuml');

        $this->fileSystem->dumpFile($dotFile, $digraphInDotFormat->value());

        $this->execute($dotFile, $imageFile);

        $image = $this->fileSystem->readFile($imageFile);

        $this->fileSystem->remove($dotFile);
        $this->fileSystem->remove($imageFile);

        return new OutputContent($image);
    }

    /**
     * @throws ImageGenerationFailure If the Graphviz command failed
     */
    private function execute(string $inputFile, string $outputFile): void
    {
        $process = new Process([$this->name->command(), '-Tpng', '-o', $outputFile, $inputFile]);
        $process->run();
        if (! $process->isSuccessful()) {
            throw ImageGenerationFailure::withOutput($process->getErrorOutput());
        }
    }
}
