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
use Webmozart\Assert\Assert;

final class GraphvizConfiguration
{
    private AssociationsBuilder $associationsBuilder;

    private DigraphStyle $digraphStyle;

    /** @param mixed[] $options */
    public function __construct(array $options)
    {
        Assert::boolean($options['associations'], 'Generate digraph associations option must be a boolean value');
        $this->associationsBuilder = $options['associations'] ? new EdgesBuilder() : new NoAssociationsBuilder();
        Assert::string($options['theme'], 'Theme option must be a string value');
        $theme = new ThemeName($options['theme']);
        Assert::boolean($options['hide-empty-blocks'], 'Hide digraph empty blocks option must be a boolean value');
        $hideEmptyBlocks = $options['hide-empty-blocks'];
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
