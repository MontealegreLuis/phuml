<?php

use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

class plPhpInterface implements HasNodeIdentifier
{
    use ObjectHashIdentifier;

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

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }
}
