<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

trait WithTypeDeclaration
{
    /** @var TypeDeclaration */
    protected $type;

    /**
     * An attribute is a reference if it has a type and it's not a built-in type
     *
     * This is used when building the digraph and the option `createAssociations` is set
     */
    public function isAReference(): bool
    {
        return $this->hasTypeDeclaration() && !$this->type->isBuiltIn();
    }

    public function hasTypeDeclaration(): bool
    {
        return $this->type->isPresent();
    }

    public function type(): TypeDeclaration
    {
        return $this->type;
    }
}
