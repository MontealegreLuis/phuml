<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Constant;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Variables\TypeDeclaration;

abstract class DefinitionBuilder
{
    /** @var Definition */
    protected $parent;

    /** @var string */
    protected $name;

    /** @var Constant[] */
    protected $constants;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->constants = [];
    }

    public function extending(Definition $parent): DefinitionBuilder
    {
        $this->parent = $parent;

        return $this;
    }

    public function withAConstant(string $name, string $type = null): DefinitionBuilder
    {
        $this->constants[] = new Constant($name, TypeDeclaration::from($type));

        return $this;
    }

    /** @return ClassDefinition|InterfaceDefinition */
    abstract public function build();
}
