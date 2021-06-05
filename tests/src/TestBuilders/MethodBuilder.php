<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Methods\Method;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

final class MethodBuilder
{
    /** @var string */
    private $name;

    /** @var Variable[] */
    private $parameters = [];

    /** @var TypeDeclaration */
    private $returnType;

    /** @var Visibility */
    private $visibility;

    public function named(string $name): MethodBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function withParameters(Variable... $parameters): MethodBuilder
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

    public function build(): Method
    {
        return new Method(
            $this->name,
            $this->visibility,
            $this->returnType ?? TypeDeclaration::absent(),
            $this->parameters
        );
    }
}
