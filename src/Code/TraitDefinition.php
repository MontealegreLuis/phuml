<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Methods\Method;
use PhUml\Code\Properties\HasProperties;
use PhUml\Code\Properties\Property;
use PhUml\Code\Properties\WithProperties;

final class TraitDefinition extends Definition implements HasProperties, UseTraits
{
    use WithProperties;
    use WithTraits;

    /**
     * @param Method[] $methods
     * @param Property[] $properties
     * @param Name[] $traits
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $properties = [],
        array $traits = []
    ) {
        parent::__construct($name, $methods);
        $this->properties = $properties;
        $this->traits = $traits;
    }

    public function hasProperties(): bool
    {
        return $this->properties !== [];
    }
}
