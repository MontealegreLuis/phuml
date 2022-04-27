<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Variables\CompositeType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

final class VariableBuilder
{
    private TypeDeclaration $type;

    public function __construct(private readonly string $name)
    {
        $this->type = TypeDeclaration::absent();
    }

    public function withType(?string $type): VariableBuilder
    {
        if ($type === null) {
            $this->type = TypeDeclaration::absent();
        } elseif (str_contains($type, '|')) {
            $this->type = TypeDeclaration::fromCompositeType(explode('|', $type), CompositeType::UNION);
        } elseif (str_contains($type, '&')) {
            $this->type = TypeDeclaration::fromCompositeType(explode('&', $type), CompositeType::INTERSECTION);
        } else {
            $this->type = TypeDeclaration::from($type);
        }

        return $this;
    }

    public function build(): Variable
    {
        return new Variable($this->name, $this->type);
    }
}
