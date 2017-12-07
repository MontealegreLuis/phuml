<?php

class plPhpClass
{
    /** @var string */
    public $name;

    /** @var plPhpAttribute[] */
    public $attributes;

    /** @var plPhpFunction[] */
    public $functions;

    /** @var plPhpInterface[] */
    public $implements;

    /** @var plPhpClass */
    public $extends;

    public function __construct(
        string $name,
        array $attributes = [],
        array $functions = [],
        array $implements = [],
        $extends = null
    ) {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->functions = $functions;
        $this->implements = $implements;
        $this->extends = $extends;
    }

    public function identifier(): string
    {
        return spl_object_hash($this);
    }

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }
}
