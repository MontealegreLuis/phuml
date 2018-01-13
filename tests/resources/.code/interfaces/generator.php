<?php

abstract class plStructureGenerator
{
    public static function factory( $generator )
    {
    }

    public abstract function createStructure( array $files );
}
