<?php

class plPhpInterface
{
    /** @var string */
    public $name;

    /** @var plPhpFunction[] */
    public $functions;

    /** @var plPhpInterface */
    public $extends;

    public function __construct(string $name, array $functions = [], $extends = null)
    {
        $this->name = $name;
        $this->functions = $functions;
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
