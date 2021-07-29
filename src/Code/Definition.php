<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

/**
 * Base class for interfaces, classes and traits
 */
abstract class Definition implements Named, HasNodeIdentifier
{
    use WithName;
    use ObjectHashIdentifier;

    /** @var Method[] */
    protected array $methods;

    /** @param Method[] $methods */
    public function __construct(Name $name, array $methods = [])
    {
        $this->name = $name;
        $this->methods = $methods;
    }

    /**
     * This method is used by the Summary class to count how many methods by visibility in a
     * Structure are
     *
     * @see Summary::methodsSummary() for more details
     */
    public function countMethodsByVisibility(Visibility $modifier): int
    {
        return \count(array_filter(
            $this->methods,
            static fn (Method $method): bool => $method->hasVisibility($modifier)
        ));
    }

    /**
     * This method is used when the commands are called with the option `hide-empty-blocks`
     *
     * For interfaces it counts the number of constants.
     * For classes it counts both constants and attributes.
     *
     * @see ClassDefinition::hasAttributes() for more details
     * @see InterfaceDefinition::hasAttributes() for more details
     */
    abstract public function hasAttributes(): bool;

    /** @return Method[] */
    public function methods(): array
    {
        return $this->methods;
    }
}
