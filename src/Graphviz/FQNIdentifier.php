<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Code\WithName;

/**
 * `Definition`s are required to have unique identifiers, so we can determine relationships between them
 *
 * Definitions identifiers are generated using their Fully Qualified Name (FQN)
 */
trait FQNIdentifier
{
    use WithName;

    public function identifier(): string
    {
        return $this->name->fullName();
    }
}
