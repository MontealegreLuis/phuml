<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Constant;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;

abstract class DefinitionBuilder
{
    /** @var Constant[] */
    protected array $constants;

    public function __construct(protected string $name)
    {
        $this->constants = [];
    }

    /** @return ClassBuilder|InterfaceBuilder */
    public function withAConstant(string $name, string $type = null): DefinitionBuilder
    {
        $this->constants[] = new Constant($name, TypeDeclaration::from($type), Visibility::public());

        return $this;
    }

    abstract public function build(): ClassDefinition|InterfaceDefinition;
}
