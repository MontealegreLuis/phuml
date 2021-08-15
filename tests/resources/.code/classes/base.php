<?php
namespace phuml;

class plBase
{
    private const BOOL    = false;
    protected const STRING  = 'hello!';
    public const NUMBER = 3;

    /** @var array */
    private static $autoload = array();

    /** @var string[] */
    private static $autoloadDirectory = array();

    /**
     * @param string $classname
     * @return void
     */
    public static function autoload( $classname )
    {
    }

    /**
     * @param string $directory
     * @return void
     */
    public static function addAutoloadDirectory( $directory )
    {
    }

    /** @return string[] */
    public static function getAutoloadClasses()
    {
    }
}
