<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Configuration\DigraphConfiguration;
use PhUml\Processors\GraphvizProcessor;

final class GraphvizProcessorBuilder
{
    private bool $associations = false;

    private bool $withoutEmptyBlocks = false;

    private string $theme = 'phuml';

    public function withAssociations(): GraphvizProcessorBuilder
    {
        $this->associations = true;
        return $this;
    }

    public function withoutEmptyBlocks(): GraphvizProcessorBuilder
    {
        $this->withoutEmptyBlocks = true;
        return $this;
    }

    public function withTheme(string $theme): GraphvizProcessorBuilder
    {
        $this->theme = $theme;
        return $this;
    }

    public function build(): GraphvizProcessor
    {
        return GraphvizProcessor::fromConfiguration(new DigraphConfiguration([
            'recursive' => true,
            'associations' => $this->associations,
            'hide-empty-blocks' => $this->withoutEmptyBlocks,
            'theme' => $this->theme,
        ]));
    }
}
