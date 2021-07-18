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
    private SplFileInfo $filePath;

    public function __construct(string $filePath)
    {
        Assert::stringNotEmpty(trim($filePath), 'Output file path cannot be empty');
        $this->filePath = new SplFileInfo(trim($filePath));
    }

    public function value(): string
    {
        return $this->filePath->getPathname();
    }
}
