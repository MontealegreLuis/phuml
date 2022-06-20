<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use SplFileInfo;
use Webmozart\Assert\Assert;

final class CodebaseDirectory
{
    private readonly string $directory;

    public function __construct(string $path)
    {
        $this->directory = $this->getAbsolutePath($path);
    }

    public function absolutePath(): string
    {
        return $this->directory;
    }

    private function getAbsolutePath(string $path): string
    {
        Assert::stringNotEmpty(
            $path,
            'The directory with the code to be scanned cannot be empty'
        );
        $directory = new SplFileInfo($path);
        if (! $directory->isDir()) {
            throw InvalidDirectory::notFoundAt($directory);
        }
        return $directory->getRealPath();
    }
}
