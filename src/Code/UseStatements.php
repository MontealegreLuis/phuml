<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

final class UseStatements
{
    /** @param UseStatement[] $useStatements */
    public function __construct(private array $useStatements)
    {
    }

    public function fullyQualifiedNameFor(Name $name): string
    {
        foreach ($this->useStatements as $useStatement) {
            if ($useStatement->endsWith($name)) {
                return $useStatement->fullyQualifiedName($name);
            }
            if ($useStatement->includes($name)) {
                return $useStatement->merge($name);
            }
            if ($useStatement->isAliasedAs($name)) {
                return $useStatement->fullyQualifiedName($name);
            }
        }
        return (string) $name;
    }
}
