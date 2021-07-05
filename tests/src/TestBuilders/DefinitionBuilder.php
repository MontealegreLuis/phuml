<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Constant;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;

abstract class DefinitionBuilder
{
    protected string $name;

    /** @var Constant[] */
    protected array $constants;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->constants = [];
    }

    /** @return ClassBuilder|InterfaceBuilder */
    public function withAConstant(string $name, string $type = null): DefinitionBuilder
    {
        $this->constants[] = new Constant($name, TypeDeclaration::from($type));

        return $this;
    }

    /** @return ClassDefinition|InterfaceDefinition */
    abstract public function build();

    /** @return NumericIdClass|NumericIdInterface */
    abstract public function buildWithNumericId();
}
