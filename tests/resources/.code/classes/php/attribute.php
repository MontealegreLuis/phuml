<?php

use phuml\plVisibility;

class plPhpAttribute
{
    private plVisibility $modifier;

    private $properties;

    public function __construct($name, $modifier = plVisibility::PUBLIC, $type = null)
    {
    }

    public function __get($key)
    {
    }

    public function __set($key, $val)
    {
    }
}
