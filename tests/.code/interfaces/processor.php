<?php

abstract class plProcessor
{
    public static function factory( $processor )
    {
    }

    public static function getProcessors()
    {
    }

    public function writeToDisk( $input, $output )
    {
    }

    abstract public function getInputTypes();
    abstract public function getOutputType();
    abstract public function process( $input, $type );

}
