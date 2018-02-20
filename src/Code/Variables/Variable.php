<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PhUml\Code\Name;

/**
 * It represents a variable declaration
 */
class Variable implements HasType
{
    use WithTypeDeclaration;

    /** @var string */
    protected $name;

    protected function __construct(string $name, TypeDeclaration $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public static function declaredWith(string $name, TypeDeclaration $type = null): Variable
    {
        return new Variable($name, $type ?? TypeDeclaration::absent());
    }

    public function __toString()
    {
        return sprintf(
            '%s%s',
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }

    public function typeName(): Name
    {
        return $this->type->name();
    }
}
