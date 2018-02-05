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
use PhUml\Code\Variables\Variable;

/**
 * It represents a class or interface method
 *
 * It doesn't distinguish neither static methods nor return types yet
 */
class Method implements HasVisibility, CanBeAbstract, CanBeStatic
{
    use WithVisibility, WithAbstractModifier, WithStaticModifier;

    /** @var string */
    private $name;

    /** @var Variable[] */
    private $parameters;

    /** @var TypeDeclaration */
    private $returnType;

    protected function __construct(
        string $name,
        Visibility $modifier,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ) {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->parameters = $parameters;
        $this->isAbstract = false;
        $this->isStatic = false;
        $this->returnType = $returnType;
    }

    /** @param Variable[] $parameters */
    public static function public(
        string $name,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ): Method {
        return new static($name, Visibility::public(), $parameters, $returnType ?? TypeDeclaration::absent());
    }

    /** @param Variable[] $parameters */
    public static function protected(
        string $name,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ): Method {
        return new static($name, Visibility::protected(), $parameters, $returnType ?? TypeDeclaration::absent());
    }

    /** @param Variable[] $parameters */
    public static function private(
        string $name,
        array $parameters = [],
        TypeDeclaration $returnType = null
    ): Method {
        return new static($name, Visibility::private(), $parameters, $returnType ?? TypeDeclaration::absent());
    }

    public function isConstructor(): bool
    {
        return $this->name === '__construct';
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function returnType(): TypeDeclaration
    {
        return $this->returnType;
    }

    public function __toString()
    {
        return sprintf(
            '%s%s%s%s',
            $this->modifier,
            $this->name,
            empty($this->parameters) ? '()' : '( ' . implode($this->parameters, ', ') . ' )',
            $this->returnType->isPresent() ? ": {$this->returnType()}" : ''
        );
    }
}
