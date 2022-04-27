<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Parser\CodebaseDirectory;
use PhUml\Processors\OutputFilePath;

final class GeneratorInput
{
    private readonly CodebaseDirectory $directory;

    private readonly OutputFilePath $outputFile;

    /** @param string[] $input */
    public static function dotFile(array $input): GeneratorInput
    {
        return new GeneratorInput($input, 'gv');
    }

    /** @param string[] $input */
    public static function textFile(array $input): GeneratorInput
    {
        return new GeneratorInput($input, 'txt');
    }

    /** @param string[] $input */
    public static function pngFile(array $input): GeneratorInput
    {
        return new GeneratorInput($input, 'png');
    }

    /** @param string[] $input */
    private function __construct(array $input, string $extension)
    {
        $this->directory = new CodebaseDirectory($input['directory'] ?? '');
        $this->outputFile = OutputFilePath::withExpectedExtension($input['output'] ?? '', $extension);
    }

    public function filePath(): OutputFilePath
    {
        return $this->outputFile;
    }

    public function codebaseDirectory(): CodebaseDirectory
    {
        return $this->directory;
    }
}
