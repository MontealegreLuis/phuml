<?php

abstract class plProcessor
{
    public static function getProcessors()
    {
        $processors = [
            'Graphviz',
            'Neato',
            'Dot',
            'Statistics',
        ];
        return $processors;
    }

    public function writeToDisk($input, $output)
    {
        file_put_contents($output, $input);
    }

    abstract public function getInputType(): string;

    abstract public function getOutputType(): string;

    abstract public function process($input, $type);
}
