<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

class ParameterBuilder
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function withType(string $type): ParameterBuilder
    {
        $this->type = $type;

        return $this;
    }

    public function build(): Variable
    {
        return Variable::declaredWith($this->name, TypeDeclaration::from($this->type));
    }
}
