<?php

class plPhpVariable
{
    /** @var string */
    public $name;

    /** @var plPhpTypeDeclaration */
    public $type;

    public function __construct(string $name, string $type = null)
    {
        $this->name = $name;
        $this->type = new plPhpTypeDeclaration($type);
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
