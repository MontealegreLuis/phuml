<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;

final class NumericIdInterface extends InterfaceDefinition
{
    private static $id = 0;

    /** @var int */
    private $identifier;

    /**
     * @param \PhUml\Code\Methods\Method[] $methods
     * @param \PhUml\Code\Attributes\Constant[] $constants
     * @param \PhUml\Code\InterfaceDefinition[] $parents
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $constants = [],
        array $parents = []
    ) {
        parent::__construct($name, $methods, $constants, $parents);
        self::$id++;
        $this->identifier = self::$id;
    }

    public function identifier(): string
    {
        return (string) $this->identifier;
    }

    public static function reset(): void
    {
        self::$id = 0;
    }
}
