<?php

class plProcessorOptionException extends Exception
{
    const READ = 1;
    const WRITE = 2;
    const UNKNOWN = 3;

    public function __construct( $key, $type )
    {
    }
}
