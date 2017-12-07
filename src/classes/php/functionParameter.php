<?php

class plPhpFunctionParameter
{
    /** @var string */
    public $name;

    /** @var string */
    public $type;

    public function __construct(string $name, string $type = null)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function __toString()
    {
        return sprintf(
            '%s%s',
            $this->type ? "{$this->type} " : '',
            $this->name
        );
    }
}
