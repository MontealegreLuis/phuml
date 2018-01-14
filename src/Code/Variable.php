<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class Variable
{
    /** @var string */
    public $name;

    /** @var TypeDeclaration */
    public $type;

    public function __construct(string $name, TypeDeclaration $type = null)
    {
        $this->name = $name;
        $this->type = $type ?? TypeDeclaration::absent();
    }

    public function hasType(): bool
    {
        return $this->type->isPresent();
    }

    public function isBuiltIn(): bool
    {
        return $this->type->isBuiltIn();
    }

    public function __toString()
    {
        return sprintf(
            '%s%s',
            $this->type->isPresent() ? "{$this->type} " : '',
            $this->name
        );
    }
}
