<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use PhUml\Code\Name;
use PhUml\Code\Name as TraitName;
use PhUml\Code\TraitDefinition;

/**
 * It builds a `TraitDefinition`
 *
 * @see MembersBuilder for more details
 */
class TraitDefinitionBuilder
{
    /** @var MembersBuilder */
    protected $membersBuilder;

    public function __construct(MembersBuilder $membersBuilder = null)
    {
        $this->membersBuilder = $membersBuilder ?? new MembersBuilder();
    }

    public function build(Trait_ $trait): TraitDefinition
    {
        return new TraitDefinition(
            Name::from($trait->name),
            $this->membersBuilder->methods($trait->getMethods()),
            $this->membersBuilder->attributes($trait->stmts),
            $this->buildTraits($trait->stmts)
        );
    }

    /** @param Node[] $nodes */
    protected function buildTraits(array $nodes): array
    {
        $useStatements = array_filter($nodes, function (Node $node) {
            return $node instanceof TraitUse;
        });

        if (empty($useStatements)) {
            return [];
        }

        $traits = [];
        /** @var TraitUse  $use */
        foreach ($useStatements as $use) {
            $traits = $this->traitNames($use, $traits);
        }

        return $traits;
    }

    /**
     * @param Name[] $traits
     * @return TraitName[]
     */
    protected function traitNames(TraitUse $use, array $traits): array
    {
        foreach ($use->traits as $name) {
            $traits[] = TraitName::from($name->getLast());
        }
        return $traits;
    }
}
