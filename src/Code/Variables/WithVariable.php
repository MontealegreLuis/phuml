<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

trait WithVariable
{
    private Variable $variable;

    public function hasTypeDeclaration(): bool
    {
        return $this->variable->hasTypeDeclaration();
    }

    public function type(): TypeDeclaration
    {
        return $this->variable->type();
    }
}
