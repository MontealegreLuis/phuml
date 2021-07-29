<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Parameters\Parameter;
use PhUml\Code\Variables\TypeDeclaration;

final class MethodBuilder
{
    /** @var Parameter[] */
    private array $parameters = [];

    private ?TypeDeclaration $returnType = null;

    private ?Visibility $visibility = null;

    private bool $isAbstract;

    private bool $isStatic;

    public function __construct(private string $name)
    {
        $this->isAbstract = false;
        $this->isStatic = false;
    }

    public function withParameters(Parameter ...$parameters): MethodBuilder
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function withReturnType(string $type): MethodBuilder
    {
        $this->returnType = TypeDeclaration::from($type);
        return $this;
    }

    public function private(): MethodBuilder
    {
        $this->visibility = new Visibility('private');
        return $this;
    }

    public function public(): MethodBuilder
    {
        $this->visibility = new Visibility('public');
        return $this;
    }

    public function protected(): MethodBuilder
    {
        $this->visibility = new Visibility('protected');
        return $this;
    }

    public function abstract(): MethodBuilder
    {
        $this->isAbstract = true;
        return $this;
    }

    public function static(): MethodBuilder
    {
        $this->isStatic = true;
        return $this;
    }

    public function build(): Method
    {
        return new Method(
            $this->name,
            $this->visibility,
            $this->returnType ?? TypeDeclaration::absent(),
            $this->parameters,
            $this->isAbstract,
            $this->isStatic
        );
    }
}
