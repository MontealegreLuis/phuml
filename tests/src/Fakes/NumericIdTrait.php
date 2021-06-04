<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

final class NumericIdTrait extends TraitDefinition
{
    private static $id = 200;

    /** @var int */
    private $identifier;

    /**
     * @param \PhUml\Code\Methods\Method[] $methods
     * @param \PhUml\Code\Attributes\Attribute[] $attributes
     * @param Name[] $traits
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $attributes = [],
        array $traits = []
    ) {
        parent::__construct($name, $methods, $attributes, $traits);
        self::$id++;
        $this->identifier = self::$id;
    }

    public function identifier(): string
    {
        return (string)$this->identifier;
    }

    public static function reset(): void
    {
        self::$id = 200;
    }
}
