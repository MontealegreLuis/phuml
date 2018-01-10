<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;


abstract class Processor
{
    public function writeToDisk(string $contents, string $filePath): void
    {
        file_put_contents($filePath, $contents);
    }

    abstract public function name(): string;
}
