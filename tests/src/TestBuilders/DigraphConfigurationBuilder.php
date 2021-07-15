<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Configuration\DigraphConfiguration;

final class DigraphConfigurationBuilder
{
    private bool $hideEmptyBlocks = false;

    private string $theme = 'phuml';

    private bool $associations = false;

    public function withoutEmptyBlocks(): DigraphConfigurationBuilder
    {
        $this->hideEmptyBlocks = true;
        return $this;
    }

    public function withTheme(string $theme): DigraphConfigurationBuilder
    {
        $this->theme = $theme;
        return $this;
    }

    public function withAssociations(): DigraphConfigurationBuilder
    {
        $this->associations = true;
        return $this;
    }

    public function build(): DigraphConfiguration
    {
        return new DigraphConfiguration([
            'theme' => $this->theme,
            'hide-empty-blocks' => $this->hideEmptyBlocks,
            'associations' => $this->associations,
        ]);
    }
}
