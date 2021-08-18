<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Names;

use PhpParser\Node\Name;
use PhUml\Code\Name as DefinitionName;

trait InterfaceNamesBuilder
{
    /**
     * @param Name[] $implements
     * @return DefinitionName[]
     */
    private function buildInterfaces(array $implements): array
    {
        return array_map(static fn (Name $name): DefinitionName => new DefinitionName((string) $name), $implements);
    }
}
