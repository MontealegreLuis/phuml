<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\WithTypeDeclaration;

class Constant implements HasType
{
    use WithTypeDeclaration;

    /** @var string */
    private $name;

    public function __construct(string $name, TypeDeclaration $type = null)
    {
        $this->name = $name;
        $this->type = $type ?? TypeDeclaration::absent();
    }

    public function __toString()
    {
        return sprintf(
            "+%s%s",
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }
}
