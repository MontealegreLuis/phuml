<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PhUml\Code\Name;

trait WithVariable
{
    private Variable $variable;

    public function isAReference(): bool
    {
        return $this->variable->isAReference();
    }

    public function referenceName(): Name
    {
        return $this->variable->referenceName();
    }

    public function hasTypeDeclaration(): bool
    {
        return $this->variable->hasTypeDeclaration();
    }

    public function type(): TypeDeclaration
    {
        return $this->variable->type();
    }
}
