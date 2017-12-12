<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\InterfaceDefinition;

class NumericIdInterface extends InterfaceDefinition
{
    private static $id = 0;
    private $identifier;

    public function __construct($name, array $functions = [], $extends = null)
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
