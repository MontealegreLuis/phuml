<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\Modifiers\CanBeAbstract;
use PhUml\Code\Modifiers\CanBeStatic;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Modifiers\WithAbstractModifier;
use PhUml\Code\Modifiers\WithStaticModifier;
use PhUml\Code\Modifiers\WithVisibility;
use PhUml\Code\Parameters\Parameter;
use PhUml\Code\Variables\TypeDeclaration;
use Stringable;

/**
 * It represents a class or interface method
 */
final class Method implements HasVisibility, CanBeAbstract, CanBeStatic, Stringable
{
    use WithVisibility;
    use WithAbstractModifier;
    use WithStaticModifier;

    /** @var Parameter[] */
    private array $parameters;

    /** @param Parameter[] $parameters */
    public function __construct(
        private string $name,
        Visibility $modifier,
        private TypeDeclaration $returnType,
        array $parameters = [],
        bool $isAbstract = false,
        bool $isStatic = false
    ) {
        $this->modifier = $modifier;
        $this->parameters = $parameters;
        $this->isAbstract = $isAbstract;
        $this->isStatic = $isStatic;
    }

    /**
     * It is used by the `ClassDefinition` to extract the parameters of a constructor
     *
     * @see \PhUml\Code\ClassDefinition::hasConstructor()
     * @see \PhUml\Code\ClassDefinition::constructorParameters()
     */
    public function isConstructor(): bool
    {
        return $this->name === '__construct';
    }

    /** @return Parameter[] */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s%s',
            $this->modifier,
            $this->name,
            $this->parameters === [] ? '()' : '(' . implode(', ', $this->parameters) . ')',
            $this->returnType->isPresent() ? ": {$this->returnType}" : ''
        );
    }
}
