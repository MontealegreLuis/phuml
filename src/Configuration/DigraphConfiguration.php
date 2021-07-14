<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Graphviz\Builders\AssociationsBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\NoAssociationsBuilder;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\ThemeName;

final class DigraphConfiguration
{
    private bool $searchRecursively;

    private AssociationsBuilder $associationsBuilder;

    private DigraphStyle $digraphStyle;

    /** @param mixed[] $input */
    public function __construct(array $input)
    {
        $this->searchRecursively = (bool) $input['recursive'];
        $this->associationsBuilder = (bool) $input['associations'] ? new EdgesBuilder() : new NoAssociationsBuilder();
        $theme = new ThemeName($input['theme']);
        $this->digraphStyle = (bool) $input['hide-empty-blocks']
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

    public function searchRecursively(): bool
    {
        return $this->searchRecursively;
    }
}
