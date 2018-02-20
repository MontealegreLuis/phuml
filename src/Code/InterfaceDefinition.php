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
    /** @var Name[] */
    protected $parents;

    /**
     * @param \PhUml\Code\Attributes\Constant[] $constants
     * @param \PhUml\Code\Methods\Method[] $methods
     * @param Name[] $parents
     */
    public function __construct(
        Name $name,
        array $constants = [],
        array $methods = [],
        array $parents = []
    ) {
        parent::__construct($name, $constants, $methods);
        $this->parents = $parents;
    }

    /**
     * It is used by the `InterfaceGraphBuilder` to create the edge to represent inheritance
     *
     * @return Name[]
     * @see \PhUml\Graphviz\Builders\InterfaceGraphBuilder::extractFrom() for more details
     */
    public function parents(): array
    {
        return $this->parents;
    }

    /**
     * It is used by the `InterfaceGraphBuilder` to determine if an inheritance association should be
     * created
     *
     * @return InterfaceDefinition[]
     * @see \PhUml\Graphviz\Builders\InterfaceGraphBuilder::extractFrom() for more details
     */
    public function hasParent(): bool
    {
        return !empty($this->parents);
    }

    /**
     * This method is used when the commands are called with the option `hide-empty-blocks`
     *
     * It only counts the constants of an interface, since interfaces are not allowed to have
     * attributes
     *
     * @see Definition::hasAttributes() for more details
     */
    public function hasAttributes(): bool
    {
        return \count($this->constants) > 0;
    }
}
