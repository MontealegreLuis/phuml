<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\HasAttributes;
use PhUml\Code\Attributes\WithAttributes;

class TraitDefinition extends Definition implements HasAttributes, UseTraits
{
    use WithAttributes;
    use WithTraits;

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
        parent::__construct($name, $methods);
        $this->attributes = $attributes;
        $this->traits = $traits;
    }

    public function hasAttributes(): bool
    {
        return \count($this->attributes) > 0;
    }
}
