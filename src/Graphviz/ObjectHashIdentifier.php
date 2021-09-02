<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Code\WithName;

/**
 * Both `ClassDefinition` and `InterfaceDefinition` objects identifiers are generated using their Fully Qualified Name
 */
trait ObjectHashIdentifier
{
    use WithName;

    public function identifier(): string
    {
        return $this->name->fullName();
    }
}
