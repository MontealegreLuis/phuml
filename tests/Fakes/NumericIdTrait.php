<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

class NumericIdTrait extends TraitDefinition
{
    private static $id = 200;

    /** @var int */
    private $identifier;

    public function identifier(): string
    {
        return (string)$this->identifier;
    }

    /**
     * @param \PhUml\Code\Methods\Method[] $methods
     * @param \PhUml\Code\Attributes\Attribute[] $attributes
     */
    public function __construct(Name $name, array $methods = [], array $attributes = [])
    {
        parent::__construct($name, $methods, $attributes);
        self::$id++;
        $this->identifier = self::$id;
    }

    public static function reset(): void
    {
        self::$id = 200;
    }
}
