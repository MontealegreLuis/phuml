<?php

abstract class plProcessor
{
    /**
     * @throws plProcessorNotFoundException
     */
    public static function factory($processor): plProcessor
    {
        $classname = 'pl' . ucfirst($processor) . 'Processor';
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

    abstract public function getInputType(): string;

    abstract public function getOutputType(): string;

    abstract public function process($input);
}
