<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;


abstract class Processor
{
    public function writeToDisk(string $input, string $output): void
    {
        file_put_contents($output, $input);
    }

    abstract public function name(): string;

    abstract public function getInputType(): string;

    abstract public function getOutputType(): string;

    abstract public function process($input);
}
