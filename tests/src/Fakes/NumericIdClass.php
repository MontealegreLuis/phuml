<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Methods\Method;
use PhUml\Code\Name;

final class NumericIdClass extends ClassDefinition
{
    private static int $id = 100;

    private int $identifier;

    /**
     * @param Method[] $methods
     * @param Constant[] $constants
     * @param Attribute[] $attributes
     * @param Name[] $interfaces
     * @param Name[] $traits
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $constants = [],
        Name $parent = null,
        array $attributes = [],
        array $interfaces = [],
        array $traits = []
    ) {
        parent::__construct($name, $methods, $constants, $parent, $attributes, $interfaces, $traits);
        self::$id++;
        $this->identifier = self::$id;
    }

    public function identifier(): string
    {
        return (string) $this->identifier;
    }

    public static function reset(): void
    {
        self::$id = 100;
    }
}
