<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Properties\Property;
use PhUml\Code\Variables\TypeDeclaration;

final class AttributeBuilder
{
    private ?Visibility $visibility = null;

    private ?TypeDeclaration $type = null;

    private bool $isStatic;

    public function __construct(private readonly string $name)
    {
        $this->isStatic = false;
    }

    public function public(): AttributeBuilder
    {
        $this->visibility = Visibility::public();
        return $this;
    }

    public function private(): AttributeBuilder
    {
        $this->visibility = Visibility::private();
        return $this;
    }

    public function protected(): AttributeBuilder
    {
        $this->visibility = Visibility::protected();
        return $this;
    }

    public function withType(?string $type): AttributeBuilder
    {
        $this->type = $type === null ? TypeDeclaration::absent() : TypeDeclaration::from($type);
        return $this;
    }

    public function static(): AttributeBuilder
    {
        $this->isStatic = true;
        return $this;
    }

    public function build(): Property
    {
        if ($this->type === null) {
            $type = null;
        } else {
            $type = $this->type->isPresent() ? (string) $this->type : null;
        }

        $variable = A::variable($this->name)
            ->withType($type)
            ->build();

        return new Property($variable, $this->visibility, $this->isStatic);
    }
}
