<?php
namespace phuml;

use phuml\interfaces\plProcessor;

class plPhuml
{
    protected $properties;

    private $files;

    /** @var plProcessor[] */
    private $processors;

    public function __construct()
    {
    }

    /** @return void */
    public function addFile( $file )
    {
    }

    public function addDirectory( $directory, $extension = 'php', $recursive = true )
    {
    }

    /** @param  plProcessor $processor */
    public function addProcessor( $processor )
    {
    }

    private function checkProcessorCompatibility( $first, $second )
    {
    }

    public function generate( $outfile, ?string $format ): ?string
    {
    }

    public function __get( string|int $key )
    {
    }

    public function __set( string|int $key, $val )
    {
    }
}
