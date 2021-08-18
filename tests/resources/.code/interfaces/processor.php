<?php
namespace phuml\interfaces;

abstract class plProcessor implements plCompatible
{
    use plDiskWriter;

    public static function factory( $processor )
    {
    }

    public static function getProcessors()
    {
    }

    abstract public function process( $input, $type );
}
