<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Graphviz\Styles\ThemeName;

final class DigraphConfiguration
{
    private bool $searchRecursively;

    private bool $extractAssociations;

    private bool $hideEmptyBlocks;

    private ThemeName $theme;

    /** @param mixed[] $input */
    public function __construct(array $input)
    {
        $this->searchRecursively = (bool) $input['recursive'];
        $this->extractAssociations = (bool) $input['associations'];
        $this->hideEmptyBlocks = (bool) $input['hide-empty-blocks'];
        $this->theme = new ThemeName($input['theme']);
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function searchRecursively(): bool
    {
        return $this->searchRecursively;
    }

    public function hideEmptyBlocks(): bool
    {
        return $this->hideEmptyBlocks;
    }

    public function theme(): ThemeName
    {
        return $this->theme;
    }
}
