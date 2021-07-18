<?php declare(strict_types=1);
/**
 * PHP version 8.0
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

    /** @param mixed[] $options */
    public function __construct(array $options)
    {
        $this->extractAssociations = (bool) ($options['associations'] ?? false);
        $this->hidePrivate = (bool) ($options['hide-private'] ?? false);
        $this->hideProtected = (bool) ($options['hide-protected'] ?? false);
        $this->hideAttributes = (bool) ($options['hide-attributes'] ?? false);
        $this->hideMethods = (bool) ($options['hide-methods'] ?? false);
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
