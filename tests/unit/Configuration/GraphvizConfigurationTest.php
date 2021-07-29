<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Styles\UnknownTheme;
use PhUml\Processors\GraphvizConfiguration;

final class GraphvizConfigurationTest extends TestCase
{
    /** @test */
    function it_fails_to_set_an_invalid_theme_name()
    {
        $this->expectException(UnknownTheme::class);

        new GraphvizConfiguration($this->options([
            'theme' => 'not-a-valid-theme-name',
        ]));
    }

    /** @test */
    function it_casts_to_bool_all_options_but_theme()
    {
        $options = $this->options([
            'associations' => 'true',
            'hide-empty-blocks' => 1,
        ]);
        $configuration = new GraphvizConfiguration($options);

        $this->assertInstanceOf(EdgesBuilder::class, $configuration->associationsBuilder());
        $this->assertStringContainsString('empty', $configuration->digraphStyle()->methods());
        $this->assertStringContainsString('empty', $configuration->digraphStyle()->attributes());
    }

    /** @test */
    function it_shows_empty_blocks_by_default()
    {
        $options = $this->options();
        unset($options['hide-empty-blocks']);
        $configuration = new GraphvizConfiguration($options);

        $this->assertInstanceOf(EdgesBuilder::class, $configuration->associationsBuilder());
        $this->assertStringContainsString('methods', $configuration->digraphStyle()->methods());
        $this->assertStringContainsString('attributes', $configuration->digraphStyle()->attributes());
    }

    private function options(array $override = []): array
    {
        return array_merge([
            'recursive' => true,
            'associations' => true,
            'hide-empty-blocks' => true,
            'theme' => 'phuml',
        ], $override);
    }
}
