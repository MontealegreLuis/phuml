<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;


abstract class Processor
{
    public const INITIAL_INPUT_TYPE = 'application/phuml-structure';

    public function writeToDisk(string $input, string $output): void
    {
        file_put_contents($output, $input);
    }

    public function isCompatibleWith(Processor $nextProcessor): bool
    {
        return $this->getOutputType() === $nextProcessor->getInputType();
    }

    public function isInitial(): bool
    {
        return self::INITIAL_INPUT_TYPE === $this->getInputType();
    }

    abstract public function name(): string;

    abstract public function getInputType(): string;

    abstract public function getOutputType(): string;

    abstract public function process($input);
}
