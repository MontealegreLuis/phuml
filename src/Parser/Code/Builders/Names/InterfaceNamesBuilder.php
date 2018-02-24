<?php
/**
 * PHP version 7.1
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
    protected function buildInterfaces(array $implements): array
    {
        return array_map(function (Name $name) {
            return DefinitionName::from($name->getLast());
        }, $implements);
    }
}
