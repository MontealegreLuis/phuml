<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Code\Attributes\Constant;
use PhUml\Code\Variables\TypeDeclaration;

abstract class DefinitionBuilder
{
    /** @var string */
    protected $name;

    /** @var Constant[] */
    protected $constants;

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

    /** @return \PhUml\Code\ClassDefinition|\PhUml\Code\InterfaceDefinition */
    abstract public function build();

    /** @return \PhUml\Fakes\NumericIdClass|\PhUml\Fakes\NumericIdInterface */
    abstract public function buildWithNumericId();
}
