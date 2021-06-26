<?php

class plBasePropertyException extends Exception
{
    private const READ = 1;
    protected const WRITE = 2;
    public const READ_WRITE = 3;
    const EXECUTE = 3;

    public function __construct( $key, $type )
    {
    }
}
