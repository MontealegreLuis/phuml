<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

class plNumericIdInterface extends plPhpInterface
{
    private static $id = 0;
    private $identifier;

    public function __construct($name, array $functions = array(), $extends = null)
    {
        parent::__construct($name, $functions, $extends);
        self::$id++;
        $this->identifier = self::$id;
    }


    public function identifier(): string
    {
        return (string)$this->identifier;
    }
}
