<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Methods\Method;
use PhUml\Code\Variable;

class InterfaceBuilder extends DefinitionBuilder
{
    /** @var Method[] */
    private $methods = [];

    public function withAPublicMethod(string $name, Variable ...$parameters): InterfaceBuilder
    {
        $this->methods[] = Method::public($name, $parameters);

        return $this;
    }

    public function withParent(InterfaceDefinition $parent): InterfaceBuilder
    {
        $this->parent = $parent;

        return $this;
    }

    /** @return InterfaceDefinition */
    public function build()
    {
        return new InterfaceDefinition($this->name, [], $this->methods, $this->parent);
    }
}
