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

    protected MembersBuilder $membersBuilder;

    public function __construct(MembersBuilder $membersBuilder = null)
    {
        $this->membersBuilder = $membersBuilder ?? new MembersBuilder();
    }

    public function build(Trait_ $trait): TraitDefinition
    {
        return new TraitDefinition(
            new Name((string) $trait->name),
            $this->membersBuilder->methods($trait->getMethods()),
            $this->membersBuilder->attributes($trait->stmts),
            $this->buildTraits($trait->stmts)
        );
    }
}
