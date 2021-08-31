<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use SplFileInfo;
use Webmozart\Assert\Assert;

final class OutputFilePath
{
    public static function withExpectedExtension(string $filePath, string $expectedExtension): OutputFilePath
    {
        $path = new SplFileInfo(trim($filePath));
        $extension = $path->getExtension();
        $directory = realpath($path->getPath());
        Assert::true($directory !== false, "Directory '{$path->getPath()}' does not exist");
        Assert::eq(
            $extension,
            $expectedExtension,
            "Output file is expected to have extension '.{$expectedExtension}', '.{$extension}' given"
        );

        return new OutputFilePath(new SplFileInfo($directory . '/' . $path->getBasename()));
    }

    private function __construct(private SplFileInfo $filePath)
    {
    }

    public function value(): string
    {
        return $this->filePath->getPathname();
    }
}
