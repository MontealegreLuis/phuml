<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Code;

class Definition
{
    /** @var string */
    public $name;

    /** @var Method[] */
    public $functions;

    /** @var Definition */
    public $extends;

    /**
     * @param Method[] $methods
     */
    public function __construct(string $name, array $methods = [], Definition $extends = null)
    {
        $this->name = $name;
        $this->functions = $methods;
        $this->extends = $extends;
    }
}
