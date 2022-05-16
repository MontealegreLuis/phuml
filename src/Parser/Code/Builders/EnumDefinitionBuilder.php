<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Stmt\Enum_;
use PhUml\Code\EnumDefinition;
use PhUml\Code\Name;
use PhUml\Parser\Code\Builders\Names\InterfaceNamesBuilder;
use PhUml\Parser\Code\Builders\Names\TraitNamesBuilder;

final class EnumDefinitionBuilder
{
    use InterfaceNamesBuilder;
    use TraitNamesBuilder;

    public function __construct(
        private readonly MembersBuilder $membersBuilder,
        private readonly UseStatementsBuilder $useStatementsBuilder,
    ) {
    }

    public function build(Enum_ $enum): EnumDefinition
    {
        $useStatements = $this->useStatementsBuilder->build($enum);

        return new EnumDefinition(
            new Name((string) $enum->namespacedName),
            $this->membersBuilder->enumCases($enum->stmts),
            $this->membersBuilder->methods($enum->getMethods(), $useStatements),
            $this->membersBuilder->constants($enum->stmts),
            $this->buildInterfaces($enum->implements),
            $this->buildTraits($enum->stmts),
        );
    }
}
