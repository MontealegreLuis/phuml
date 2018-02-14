<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

/**
 * Base class for interfaces and classes
 *
 * It does not support traits yet
 */
abstract class Definition implements HasNodeIdentifier
{
    use ObjectHashIdentifier;

    /** @var string */
    protected $name;

    /** @var \PhUml\Code\Attributes\Constant[] */
    protected $constants;

    /** @var Method[] */
    protected $methods;

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
        $this->name = $name;
        $this->constants = $constants;
        $this->methods = $methods;
        $this->extends = $parent;
    }

    /**
     * The name of a definition is used by the `Structure` class to avoid duplicated definitions
     *
     * @see Structure::has
     * @see Structure::get
     */
    public function name(): string
    {
        return $this->name;
    }

    /** @return \PhUml\Code\Attributes\Constant[] */
    public function constants(): array
    {
        return $this->constants;
    }

    abstract public function hasAttributes(): bool;

    /** @return Method[] */
    public function methods(): array
    {
        return $this->methods;
    }

    public function extends(): Definition
    {
        return $this->extends;
    }

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }

    /**
     * This method is used by the Summary class to count how many methods by visibility in a
     * Structure are
     *
     * @see Summary::methodsSummary() for more details
     */
    public function countMethodsByVisibility(Visibility $modifier): int
    {
        return \count(array_filter($this->methods, function (Method $method) use ($modifier) {
            return $method->hasVisibility($modifier);
        }));
    }
}
