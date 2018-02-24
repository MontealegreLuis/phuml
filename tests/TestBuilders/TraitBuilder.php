<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;
use PhUml\Fakes\NumericIdTrait;

class TraitBuilder
{
    use MembersBuilder;

    /** @var string */
    private $name;

    /** @var Name[] */
    private $traits = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function using(Name ...$traits): TraitBuilder
    {
        $this->traits = $traits;

        return $this;
    }

    public function build(): TraitDefinition
    {
        return new TraitDefinition(
            Name::from($this->name),
            $this->methods,
            $this->attributes,
            $this->traits
        );
    }

    public function buildWithNumericId(): NumericIdTrait
    {
        return new NumericIdTrait(
            Name::from($this->name),
            $this->methods,
            $this->attributes,
            $this->traits
        );
    }
}
