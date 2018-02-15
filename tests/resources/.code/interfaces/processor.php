<?php

abstract class plProcessor implements plCompatible
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

    abstract public function process( $input, $type );
}
