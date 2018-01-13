<?php

class plProcessorOptions
{
    const BOOL    = 1;
    const STRING  = 2;
    const DECIMAL = 3;

    protected $properties = array();

    public function __get( $key )
    {
    }

    public function __set( $key, $val )
    {
    }

    public function getOptions()
    {
    }

    public function getOptionDescription( $option )
    {
    }

    public function getOptionType( $option )
    {
    }
}
