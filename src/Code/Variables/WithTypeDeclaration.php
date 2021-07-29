<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

trait WithTypeDeclaration
{
    protected TypeDeclaration $type;

    /** @see HasType::hasTypeDeclaration() for more details */
    public function hasTypeDeclaration(): bool
    {
        return $this->type->isPresent();
    }

    /** @see HasType::type() for more details */
    public function type(): TypeDeclaration
    {
        return $this->type;
    }
}
