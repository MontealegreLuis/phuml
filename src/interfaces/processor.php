<?php

abstract class plProcessor
{
    public static function factory( $processor )
    {
        $classname = 'pl' . ucfirst( $processor ) . 'Processor';
        if ( class_exists( $classname ) === false )
        {
            throw new plProcessorNotFoundException( $processor );
        }
        return new $classname();
    }

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

    public function writeToDisk( $input, $output )
    {
        file_put_contents( $output, $input );
    }

    abstract public function getInputTypes();
    abstract public function getOutputType();
    abstract public function process( $input, $type );

}
