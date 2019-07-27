<?php
/**
 * PHP version 7.1
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
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It represents a class or interface method
 */
class Method implements HasVisibility, CanBeAbstract, CanBeStatic
{
    use WithVisibility, WithAbstractModifier, WithStaticModifier;

    /** @var string */
    private $name;

    /** @var \PhUml\Code\Variables\Variable[] */
    private $parameters;

    /** @var TypeDeclaration */
    private $returnType;

    /** @param \PhUml\Code\Variables\Variable[] $parameters */
    public static function public(
        string $name,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ): Method {
        return new static($name, Visibility::public(), $returnType ?? TypeDeclaration::absent(), $parameters);
    }

    /** @param \PhUml\Code\Variables\Variable[] $parameters */
    public static function protected(
        string $name,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ): Method {
        return new static($name, Visibility::protected(), $returnType ?? TypeDeclaration::absent(), $parameters);
    }

    /** @param \PhUml\Code\Variables\Variable[] $parameters */
    public static function private(
        string $name,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ): Method {
        return new static($name, Visibility::private(), $returnType ?? TypeDeclaration::absent(), $parameters);
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

    /** @return \PhUml\Code\Variables\Variable[] */
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
            empty($this->parameters) ? '()' : '(' . implode(', ', $this->parameters) . ')',
            $this->returnType->isPresent() ? ": {$this->returnType}" : ''
        );
    }

    /** @param \PhUml\Code\Variables\Variable[] $parameters */
    protected function __construct(
        string $name,
        Visibility $modifier,
        TypeDeclaration $returnType,
        array $parameters = []
    ) {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->parameters = $parameters;
        $this->isAbstract = false;
        $this->isStatic = false;
        $this->returnType = $returnType;
    }
}
