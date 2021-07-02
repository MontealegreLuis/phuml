<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Methods\Method;
use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

final class NumericIdTrait extends TraitDefinition
{
    private static int $id = 200;

    private int $identifier;

    /**
     * @param Method[] $methods
     * @param Attribute[] $attributes
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
        return (string) $this->identifier;
    }

    public static function reset(): void
    {
        self::$id = 200;
    }
}
