<?php

class plBasePropertyException extends Exception
{
    const READ = 1;
    const WRITE = 2;

    public function __construct( $key, $type )
    {
    }
}
