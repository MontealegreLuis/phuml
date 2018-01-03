<?php

use PhUml\Processors\DotProcessor;
use PhUml\Processors\NeatoProcessor;

abstract class plProcessor
{
    public const INITIAL_INPUT_TYPE = 'application/phuml-structure';

    /**
     * @throws plProcessorNotFoundException
     */
    public static function factory($processor): plProcessor
    {
        $processor = ucfirst($processor);
        if ($processor === 'Dot') {
            return new DotProcessor();
        }
        if ($processor === 'Neato') {
            return new NeatoProcessor();
        }
        $classname = "pl{$processor}Processor";
        if (!class_exists($classname)) {
            throw new plProcessorNotFoundException($processor);
        }
        return new $classname();
    }

    public static function getProcessors(): array
    {
        return [
            'Graphviz',
            'Neato',
            'Dot',
            'Statistics',
        ];
    }

    public function writeToDisk(string $input, string $output): void
    {
        file_put_contents($output, $input);
    }

    public function isCompatibleWith(plProcessor $nextProcessor): bool
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
