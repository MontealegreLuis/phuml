<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

/**
 * Every processor has a name and can write their output to a file
 */
abstract class Processor
{
    public function saveToFile(OutputContent $contents, OutputFilePath $filePath): void
    {
        file_put_contents($filePath->value(), $contents->value());
    }

    abstract public function name(): string;
}
