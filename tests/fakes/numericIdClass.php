<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PhUml\Code\ClassDefinition;

class plNumericIdClass extends ClassDefinition
{
    private static $id = 100;
    private $identifier;

    public function __construct(
        string $name,
        array $attributes = [],
        array $functions = [],
        array $implements = [],
        $extends = null
    ) {
        parent::__construct($name, $attributes, $functions, $implements, $extends);
        self::$id++;
        $this->identifier = self::$id;
    }

    public function identifier(): string
    {
        return (string)$this->identifier;
    }
}
