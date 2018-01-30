<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class StaticAttribute extends Attribute
{
    public function __construct(string $name, Visibility $modifier, TypeDeclaration $type)
    {
        parent::__construct($name, $modifier, $type);
        $this->isStatic = true;
    }
}
