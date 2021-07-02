<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

final class VariableBuilder
{
    private string $name;

    private TypeDeclaration $type;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->type = TypeDeclaration::absent();
    }

    public function withType(?string $type): VariableBuilder
    {
        $this->type = $type !== null ? TypeDeclaration::from($type) : TypeDeclaration::absent();
        return $this;
    }

    public function build(): Variable
    {
        return new Variable($this->name, $this->type);
    }
}
