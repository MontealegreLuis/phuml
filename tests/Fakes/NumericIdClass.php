<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Name;

class NumericIdClass extends ClassDefinition
{
    private static $id = 100;

    /** @var int */
    private $identifier;

    public function __construct(
        Name $name,
        array $constants = [],
        array $methods = [],
        Name $parent = null,
        array $attributes = [],
        array $interfaces = []
    ) {
        parent::__construct($name, $methods, $constants, $parent, $attributes, $interfaces);
        self::$id++;
        $this->identifier = self::$id;
    }

    public function identifier(): string
    {
        return (string)$this->identifier;
    }

    public static function reset(): void
    {
        self::$id = 100;
    }
}
