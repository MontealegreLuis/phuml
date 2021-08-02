<?php declare(strict_types=1);
/**
 * PHP version 8.0
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

    public function __construct(
        private MembersBuilder $membersBuilder,
        private UseStatementsBuilder $useStatementsBuilder
    ) {
    }

    public function build(Interface_ $interface): InterfaceDefinition
    {
        $useStatements = $this->useStatementsBuilder->build($interface);
        return new InterfaceDefinition(
            new InterfaceDefinitionName((string) $interface->namespacedName),
            $this->membersBuilder->methods($interface->getMethods(), $useStatements),
            $this->membersBuilder->constants($interface->stmts),
            $this->buildInterfaces($interface->extends)
        );
    }
}
