<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Name as ParsedName;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhUml\Code\Name;
use PhUml\Code\UseStatement;
use PhUml\Code\UseStatements;

final class UseStatementsBuilder
{
    public function build(Class_|Interface_|Trait_ $definition): UseStatements
    {
        $uses = [];

        $previous = $definition->getAttribute('previous');
        while ($previous instanceof Use_ || $previous instanceof GroupUse) {
            if ($previous instanceof Use_) {
                $uses[] = array_map(fn (UseUse $use): UseStatement => $this->fromUseStatement($use), $previous->uses);
            } else {
                $prefix = (string) $previous->prefix;
                $uses[] = array_map(
                    fn (UseUse $use): UseStatement => $this->fromGroupedUse($use, $prefix),
                    $previous->uses
                );
            }
            $previous = $previous->getAttribute('previous');
        }

        return new UseStatements(array_merge(...$uses));
    }

    private function fromUseStatement(UseUse $use): UseStatement
    {
        $alias = null;
        if ($use->alias !== null) {
            $alias = new Name((string) $use->alias);
        }
        return new UseStatement(new Name((string) $use->name), $alias);
    }

    private function fromGroupedUse(UseUse $use, string $prefix): UseStatement
    {
        $alias = null;
        if ($use->alias !== null) {
            $alias = new Name((string) $use->alias);
        }
        return new UseStatement(new Name((string) ParsedName::concat($prefix, $use->name)), $alias);
    }
}
