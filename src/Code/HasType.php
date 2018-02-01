<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

interface HasType
{
    /**
     * A member is a reference if it has a type and it's not a built-in type
     *
     * This is used when building the digraph and the option `createAssociations` is set
     */
    public function isAReference(): bool;

    public function hasTypeDeclaration(): bool;

    public function type(): TypeDeclaration;
}
