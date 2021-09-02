<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\TraitDefinition;

trait WithDotLanguageAssertions
{
    public function assertNode(Definition $definition, string $dotLanguage): void
    {
        $identifier = str_replace('\\', '\\\\', $definition->identifier());
        $this->assertMatchesRegularExpression(
            "/\"{$identifier}\" \\[label=<(?:.)+{$definition->name()}(?:.)+> shape=plaintext color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "Definition {$definition->name()} with identifier {$definition->identifier()} cannot be found"
        );
    }

    public function assertInheritance(
        Definition $definition,
        Definition $parent,
        string $dotLanguage
    ): void {
        $parentIdentifier = str_replace('\\', '\\\\', $parent->identifier());
        $identifier = str_replace('\\', '\\\\', $definition->identifier());
        $this->assertMatchesRegularExpression(
            "/\"{$parentIdentifier}\" -> \"{$identifier}\" \\[dir=back arrowtail=empty style=solid color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "{$definition->name()} identified by {$definition->identifier()} does not inherits {$parent->name()} identified by {$parent->identifier()}"
        );
    }

    public function assertImplementation(
        ClassDefinition $class,
        InterfaceDefinition $interface,
        string $dotLanguage
    ): void {
        $interfaceIdentifier = str_replace('\\', '\\\\', $interface->identifier());
        $identifier = str_replace('\\', '\\\\', $class->identifier());
        $this->assertMatchesRegularExpression(
            "/\"{$interfaceIdentifier}\" -> \"{$identifier}\" \\[dir=back arrowtail=empty style=dashed color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "{$class->name()} does not implements {$interface->name()}"
        );
    }

    public function assertAssociation(
        Definition $from,
        Definition $to,
        string $dotLanguage
    ): void {
        $this->assertMatchesRegularExpression(
            "/\"{$from->identifier()}\" -> \"{$to->identifier()}\" \\[dir=back arrowtail=none style=solid color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "There is no association between {$from->name()} and {$to->name()}"
        );
    }

    public function assertUseTrait(
        ClassDefinition $class,
        TraitDefinition $trait,
        string $dotLanguage
    ): void {
        $this->assertMatchesRegularExpression(
            "/\"{$trait->identifier()}\" -> \"{$class->identifier()}\" \\[dir=back arrowtail=normal style=solid color=\"#[0-9a-f]{6}\"\\]/",
            $dotLanguage,
            "Class {$class->name()} does not use trait {$trait->name()}"
        );
    }
}
