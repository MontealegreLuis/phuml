<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

final class UseStatement
{
    public function __construct(private Name $name, private ?Name $alias)
    {
    }

    public function endsWith(Name $name): bool
    {
        return str_ends_with(haystack: (string) $this->name, needle: $name->removeArraySuffix());
    }

    public function includes(Name $name): bool
    {
        return str_ends_with(haystack: (string) $this->name, needle: $name->first());
    }

    public function isAliasedAs(Name $name): bool
    {
        return $this->alias !== null && (string) $this->alias === $name->removeArraySuffix();
    }

    public function fullyQualifiedName(Name $name): string
    {
        return $name->isArray() ? "{$this->name->fullName()}[]" : $this->name->fullName();
    }

    public function merge(Name $name): string
    {
        return "{$this->name->packageName()}\\{$name->fullName()}";
    }
}
