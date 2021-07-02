<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Graphviz\Styles\ThemeName;

final class DigraphConfiguration
{
    protected bool $searchRecursively;

    protected bool $extractAssociations;

    private bool $hideProtected;

    private bool $hidePrivate;

    private bool $hideAttributes;

    private bool $hideMethods;

    protected bool $hideEmptyBlocks;

    protected ThemeName $theme;

    /** @param mixed[] $input */
    public function __construct(array $input)
    {
        $this->searchRecursively = (bool) $input['recursive'];
        $this->extractAssociations = (bool) $input['associations'];
        $this->hidePrivate = (bool) $input['hide-private'];
        $this->hideProtected = (bool) $input['hide-protected'];
        $this->hideAttributes = (bool) $input['hide-attributes'];
        $this->hideMethods = (bool) $input['hide-methods'];
        $this->hideEmptyBlocks = (bool) $input['hide-empty-blocks'];
        $this->setTheme($input['theme']);
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function searchRecursively(): bool
    {
        return $this->searchRecursively;
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

    public function hideEmptyBlocks(): bool
    {
        return $this->hideEmptyBlocks;
    }

    public function theme(): ThemeName
    {
        return $this->theme;
    }

    protected function setTheme(string $name): void
    {
        $this->theme = new ThemeName($name);
    }
}
