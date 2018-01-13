<?php

abstract class plExternalCommandProcessor extends plProcessor
{

    abstract public function execute( $infile, $outfile, $type );

    public function process( $input, $type )
    {
    }
}
