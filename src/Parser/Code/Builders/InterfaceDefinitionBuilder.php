<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Stmt\Interface_;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name as InterfaceDefinitionName;
use PhUml\Parser\Code\Builders\Names\InterfaceNamesBuilder;

/**
 * It builds an `InterfaceDefinition`
 *
 * @see MembersBuilder
 * @see InterfaceNamesBuilder
 */
final class InterfaceDefinitionBuilder
{
    use InterfaceNamesBuilder;

    private MembersBuilder $membersBuilder;

    public function __construct(MembersBuilder $membersBuilder = null)
    {
        $this->membersBuilder = $membersBuilder ?? new MembersBuilder();
    }

    public function build(Interface_ $interface): InterfaceDefinition
    {
        return new InterfaceDefinition(
            new InterfaceDefinitionName((string) $interface->name),
            $this->membersBuilder->methods($interface->getMethods()),
            $this->membersBuilder->constants($interface->stmts),
            $this->buildInterfaces($interface->extends)
        );
    }
}
