<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

class TraitBuilder
{
    use MembersBuilder;

    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function build(): TraitDefinition
    {
        return new TraitDefinition(
            Name::from($this->name),
            $this->methods,
            $this->attributes
        );
    }
}
