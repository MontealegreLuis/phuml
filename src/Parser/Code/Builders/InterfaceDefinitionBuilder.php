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

/**
 * It builds an `InterfaceDefinition`
 *
 * @see MembersBuilder for more details
 */
class InterfaceDefinitionBuilder
{
    /** @var MembersBuilder */
    private $membersBuilder;

    public function __construct(MembersBuilder $membersBuilder = null)
    {
        $this->membersBuilder = $membersBuilder ?? new MembersBuilder();
    }

    public function build(Interface_ $interface): InterfaceDefinition
    {
        return new InterfaceDefinition(
            InterfaceDefinitionName::from($interface->name),
            $this->membersBuilder->methods($interface->getMethods()),
            $this->membersBuilder->constants($interface->stmts),
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
