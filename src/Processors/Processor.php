<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

/**
 * Every processor has a name and can write their output to a file
 */
abstract class Processor
{
    public function saveToFile(string $contents, string $filePath): void
    {
        file_put_contents($filePath, $contents);
    }

    abstract public function name(): string;
}
