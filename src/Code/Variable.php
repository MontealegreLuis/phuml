<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * It represents a variable declaration
 */
class Variable
{
    /** @var string */
    public $name;

    /** @var TypeDeclaration */
    public $type;

    protected function __construct(string $name, TypeDeclaration $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public static function declaredWith(string $name, TypeDeclaration $type = null): Variable
    {
        return new Variable($name, $type ?? TypeDeclaration::absent());
    }

    /**
     * An attribute is a reference if it has a type and it's not a built-in type
     *
     * This is used when building the digraph and the option `createAssociations` is set
     */
    public function isAReference(): bool
    {
        return $this->hasType() && !$this->isBuiltIn();
    }

    private function hasType(): bool
    {
        return $this->type->isPresent();
    }

    private function isBuiltIn(): bool
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
