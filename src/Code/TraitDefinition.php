<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\HasAttributes;
use PhUml\Code\Attributes\WithAttributes;

class TraitDefinition extends Definition implements HasAttributes
{
    use WithAttributes;

    /**
     * @param \PhUml\Code\Methods\Method[] $methods
     * @param \PhUml\Code\Attributes\Attribute[] $attributes
     */
    public function __construct(Name $name, array $methods = [], array $attributes = [])
    {
        parent::__construct($name, $methods);
        $this->attributes = $attributes;
    }

    public function hasAttributes(): bool
    {
        return \count($this->attributes) > 0;
    }
}
