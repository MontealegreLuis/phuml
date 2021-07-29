<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;

final class TraitBuilder
{
    use MembersBuilder;

    /** @var Name[] */
    private array $traits = [];

    public function __construct(private string $name)
    {
    }

    public function using(Name ...$traits): TraitBuilder
    {
        $this->traits = $traits;

        return $this;
    }

    public function build(): TraitDefinition
    {
        return new TraitDefinition(
            new Name($this->name),
            $this->methods,
            $this->attributes,
            $this->traits
        );
    }
}
