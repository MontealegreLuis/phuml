<?php declare(strict_types=1);
/**
 * PHP version 7.2
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
        return array_map(static function (Name $name): DefinitionName {
            return DefinitionName::from($name->getLast());
        }, $implements);
    }
}
