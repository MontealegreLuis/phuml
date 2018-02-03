<?php

class plBase
{
    /** @var array */
    private static $autoload = array();

    /** @var string[] */
    private static $autoloadDirectory = array();

    /**
     * @return void
     */
    public static function autoload( $classname )
    {
    }

    /** @return void */
    public static function addAutoloadDirectory( $directory )
    {
    }

    /** @return string[] */
    public static function getAutoloadClasses()
    {
    }
}
