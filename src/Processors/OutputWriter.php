<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use Symplify\SmartFileSystem\SmartFileSystem;

final class OutputWriter
{
    private SmartFileSystem $fileSystem;

    public function __construct(SmartFileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function save(OutputContent $contents, OutputFilePath $filePath): void
    {
        $this->fileSystem->dumpFile($filePath->value(), $contents->value());
    }
}
