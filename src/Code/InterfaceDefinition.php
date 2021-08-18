<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Attributes\Constant;
use PhUml\Code\Attributes\HasConstants;
use PhUml\Code\Attributes\WithConstants;
use PhUml\Code\Methods\Method;

/**
 * It represents an interface definition
 */
final class InterfaceDefinition extends Definition implements HasConstants
{
    use WithConstants;

    /** @var Name[] */
    private array $parents;

    /**
     * @param Method[] $methods
     * @param Constant[] $constants
     * @param Name[] $parents
     */
    public function __construct(
        Name $name,
        array $methods = [],
        array $constants = [],
        array $parents = []
    ) {
        parent::__construct($name, $methods);
        $this->constants = $constants;
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
     * @see \PhUml\Graphviz\Builders\InterfaceGraphBuilder::extractFrom() for more details
     */
    public function hasParent(): bool
    {
        return $this->parents !== [];
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
        return $this->constants !== [];
    }
}
