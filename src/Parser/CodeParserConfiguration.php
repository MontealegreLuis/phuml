<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

final class CodeParserConfiguration
{
    private bool $extractAssociations;

    private bool $hideProtected;

    private bool $hidePrivate;

    private bool $hideAttributes;

    private bool $hideMethods;

    /** @param mixed[] $config */
    public function __construct(array $config)
    {
        $this->extractAssociations = (bool) ($config['associations'] ?? false);
        $this->hidePrivate = (bool) ($config['hide-private'] ?? false);
        $this->hideProtected = (bool) ($config['hide-protected'] ?? false);
        $this->hideAttributes = (bool) ($config['hide-attributes'] ?? false);
        $this->hideMethods = (bool) ($config['hide-methods'] ?? false);
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function hidePrivate(): bool
    {
        return $this->hidePrivate;
    }

    public function hideProtected(): bool
    {
        return $this->hideProtected;
    }

    public function hideAttributes(): bool
    {
        return $this->hideAttributes;
    }

    public function hideMethods(): bool
    {
        return $this->hideMethods;
    }
}
