<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\Graphviz\Builders\AssociationsBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\ThemeName;

final class GraphvizConfiguration
{
    private AssociationsBuilder $associationsBuilder;

    private DigraphStyle $digraphStyle;

    /** @param mixed[] $configuration */
    public function __construct(array $configuration)
    {
        $extractAssociations = (bool) ($configuration['associations'] ?? false);
        $this->associationsBuilder = $extractAssociations ? new EdgesBuilder() : new NoAssociationsBuilder();
        $theme = new ThemeName($configuration['theme']);
        $hideEmptyBlocks = (bool) ($configuration['hide-empty-blocks'] ?? false);
        $this->digraphStyle = $hideEmptyBlocks
            ? DigraphStyle::withoutEmptyBlocks($theme)
            : DigraphStyle::default($theme);
    }

    public function associationsBuilder(): AssociationsBuilder
    {
        return $this->associationsBuilder;
    }

    public function digraphStyle(): DigraphStyle
    {
        return $this->digraphStyle;
    }
}
