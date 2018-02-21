<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Interface_;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name as InterfaceDefinitionName;
use PhUml\Parser\Code\Builders\Members\ConstantsBuilder;
use PhUml\Parser\Code\Builders\Members\MethodsBuilder;

/**
 * It builds an `InterfaceDefinition`
 *
 * @see ConstantsBuilder for more details about the constants creation
 * @see MethodsBuilder for more details about the methods creation
 */
class InterfaceDefinitionBuilder
{
    /** @var MethodsBuilder */
    private $methodsBuilder;

    /** @var ConstantsBuilder */
    private $constantsBuilder;

    public function __construct(
        ConstantsBuilder $constantsBuilder = null,
        MethodsBuilder $methodsBuilder = null
    ) {
        $this->constantsBuilder = $constantsBuilder ?? new ConstantsBuilder();
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder();
    }

    public function build(Interface_ $interface): InterfaceDefinition
    {
        return new InterfaceDefinition(
            InterfaceDefinitionName::from($interface->name),
            $this->methodsBuilder->build($interface->getMethods()),
            $this->constantsBuilder->build($interface->stmts),
            $this->buildParents($interface)
        );
    }

    /** @return InterfaceDefinitionName[] */
    protected function buildParents(Interface_ $interface): array
    {
        return array_map(function (Name $name) {
            return InterfaceDefinitionName::from($name->getLast());
        }, $interface->extends);
    }
}
