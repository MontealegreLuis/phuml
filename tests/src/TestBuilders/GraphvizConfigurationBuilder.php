<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Processors\GraphvizConfiguration;

final class GraphvizConfigurationBuilder
{
    private bool $hideEmptyBlocks = false;

    private string $theme = 'phuml';

    private bool $associations = false;

    public function withoutEmptyBlocks(): GraphvizConfigurationBuilder
    {
        $this->hideEmptyBlocks = true;
        return $this;
    }

    public function withTheme(string $theme): GraphvizConfigurationBuilder
    {
        $this->theme = $theme;
        return $this;
    }

    public function withAssociations(): GraphvizConfigurationBuilder
    {
        $this->associations = true;
        return $this;
    }

    public function build(): GraphvizConfiguration
    {
        return new GraphvizConfiguration([
            'theme' => $this->theme,
            'hide-empty-blocks' => $this->hideEmptyBlocks,
            'associations' => $this->associations,
        ]);
    }
}
