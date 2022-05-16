<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Properties\Constant;
use PhUml\Code\Variables\TypeDeclaration;

final class ConstantBuilder
{
    private Visibility $visibility;

    private TypeDeclaration $type;

    public function __construct(private readonly string $name)
    {
        $this->type = TypeDeclaration::absent();
        $this->visibility = Visibility::PUBLIC;
    }

    public function public(): ConstantBuilder
    {
        $this->visibility = Visibility::PUBLIC;
        return $this;
    }

    public function withType(string $type): ConstantBuilder
    {
        $this->type = TypeDeclaration::from($type);
        return $this;
    }

    public function private(): ConstantBuilder
    {
        $this->visibility = Visibility::PRIVATE;
        return $this;
    }

    public function protected(): ConstantBuilder
    {
        $this->visibility = Visibility::PROTECTED;
        return $this;
    }

    public function build(): Constant
    {
        return new Constant($this->name, $this->type, $this->visibility);
    }
}
