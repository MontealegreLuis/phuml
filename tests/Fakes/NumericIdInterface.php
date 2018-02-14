<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;

class NumericIdInterface extends InterfaceDefinition
{
    private static $id = 0;

    /** @var int */
    private $identifier;

    public function __construct(
        string $name,
        array $constants = [],
        array $methods = [],
        Definition $parent = null
    ) {
        parent::__construct($name, $constants, $methods, $parent);
        self::$id++;
        $this->identifier = self::$id;
    }

    public function identifier(): string
    {
        return (string)$this->identifier;
    }

    public static function reset(): void
    {
        self::$id = 0;
    }
}
