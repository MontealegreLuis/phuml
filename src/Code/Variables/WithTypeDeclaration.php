<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

trait WithTypeDeclaration
{
    /** @var TypeDeclaration */
    protected $type;

    /** @see HasType::isAReference() for more details */
    public function isAReference(): bool
    {
        return $this->hasTypeDeclaration() && ! $this->type->isBuiltIn();
    }

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
