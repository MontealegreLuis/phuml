<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * It represents an interface definition
 */
class InterfaceDefinition extends Definition
{
    /** @var Definition */
    protected $extends;

    /**
     * @param \PhUml\Code\Attributes\Constant[] $constants
     * @param Method[] $methods
     */
    public function __construct(
        string $name,
        array $constants = [],
        array $methods = [],
        Definition $parent = null
    ) {
        parent::__construct($name, $constants, $methods);
        $this->extends = $parent;
    }

    /**
     * It only counts the constants of an interface, since interfaces are not allowed to have
     * attributes
     *
     * The method name is `hasAttributes` since in a class diagram, constants are shown in the same
     * block as the instance variables (attributes)
     */
    public function hasAttributes(): bool
    {
        return \count($this->constants) > 0;
    }

    public function extends(): Definition
    {
        return $this->extends;
    }

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }
}
