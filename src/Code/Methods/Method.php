<?php declare(strict_types=1);
/**
 * PHP version 7.4
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

/**
 * It represents a class or interface method
 */
final class Method implements HasVisibility, CanBeAbstract, CanBeStatic
{
    use WithVisibility;
    use WithAbstractModifier;
    use WithStaticModifier;

    private string $name;

    /** @var Parameter[] */
    private array $parameters;

    private TypeDeclaration $returnType;

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

    public function __toString()
    {
        return sprintf(
            '%s%s%s%s',
            $this->modifier,
            $this->name,
            count($this->parameters) === 0 ? '()' : '(' . implode(', ', $this->parameters) . ')',
            $this->returnType->isPresent() ? ": {$this->returnType}" : ''
        );
    }

    /** @param Parameter[] $parameters */
    public function __construct(
        string $name,
        Visibility $modifier,
        TypeDeclaration $returnType,
        array $parameters = [],
        bool $isAbstract = false,
        bool $isStatic = false
    ) {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->parameters = $parameters;
        $this->isAbstract = $isAbstract;
        $this->isStatic = $isStatic;
        $this->returnType = $returnType;
    }
}
