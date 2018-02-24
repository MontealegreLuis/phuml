<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Names;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\TraitUse;
use PhUml\Code\Name as TraitName;

trait TraitNamesBuilder
{
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
    private function traitNames(TraitUse $use, array $traits): array
    {
        foreach ($use->traits as $name) {
            $traits[] = TraitName::from($name->getLast());
        }
        return $traits;
    }
}
