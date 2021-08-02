<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Stmt\Trait_;
use PhUml\Code\Name;
use PhUml\Code\TraitDefinition;
use PhUml\Parser\Code\Builders\Names\TraitNamesBuilder;

/**
 * It builds a `TraitDefinition`
 *
 * @see MembersBuilder
 * @see TraitNamesBuilder
 */
final class TraitDefinitionBuilder
{
    use TraitNamesBuilder;

    public function __construct(
        private MembersBuilder $membersBuilder,
        private UseStatementsBuilder $useStatementsBuilder
    ) {
    }

    public function build(Trait_ $trait): TraitDefinition
    {
        $useStatements = $this->useStatementsBuilder->build($trait);
        return new TraitDefinition(
            new Name((string) $trait->namespacedName),
            $this->membersBuilder->methods($trait->getMethods(), $useStatements),
            $this->membersBuilder->attributes($trait->stmts, $trait->getMethod('__construct'), $useStatements),
            $this->buildTraits($trait->stmts)
        );
    }
}
