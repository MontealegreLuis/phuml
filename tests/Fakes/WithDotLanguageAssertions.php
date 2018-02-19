<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;

trait WithDotLanguageAssertions
{
    public function assertNode(Definition $definition, string $dotLanguage): void
    {
        $this->assertRegExp(
            "/\"{$definition->identifier()}\" \\[label=<(?:.)+{$definition->name()}(?:.)+> shape=plaintext color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "Definition {$definition->name()} with identifier {$definition->identifier()} cannot be found"
        );
    }

    public function assertInheritance(
        Definition $definition,
        Definition $parent,
        string $dotLanguage
    ): void
    {
        $this->assertRegExp(
            "/\"{$parent->identifier()}\" -> \"{$definition->identifier()}\" \\[dir=back arrowtail=empty style=solid color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "{$definition->name()} identified by {$definition->identifier()} does not inherits {$parent->name()} identified by {$parent->identifier()}"
        );
    }

    public function assertImplementation(
        ClassDefinition $class,
        InterfaceDefinition $interface,
        string $dotLanguage
    ): void
    {
        $this->assertRegExp(
            "/\"{$interface->identifier()}\" -> \"{$class->identifier()}\" \\[dir=back arrowtail=normal style=dashed color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "{$class->name()} does not implements {$interface->name()}"
        );
    }

    public function assertAssociation(
        Definition $from,
        Definition $to,
        string $dotLanguage
    ): void
    {
        $this->assertRegExp(
            "/\"{$from->identifier()}\" -> \"{$to->identifier()}\" \\[dir=back arrowtail=none style=solid color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "There is no association between {$from->name()} and {$to->name()}"
        );
    }
}
