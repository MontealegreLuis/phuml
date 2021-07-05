<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Styles\UnknownTheme;

final class DigraphConfigurationTest extends TestCase
{
    /** @test */
    function it_fails_to_set_an_invalid_theme_name()
    {
        $this->expectException(UnknownTheme::class);

        new DigraphConfiguration($this->options([
            'theme' => 'not-a-valid-theme-name',
        ]));
    }

    /** @test */
    function it_knows_which_theme_has_been_set()
    {
        $configuration = new DigraphConfiguration($this->options([
            'theme' => 'php',
        ]));

        $this->assertEquals('php', $configuration->theme()->name());
    }

    /** @test */
    function it_casts_to_bool_all_options_but_theme()
    {
        $options = $this->options([
            'recursive' => 0,
            'associations' => 'true',
            'hide-private' => 1,
            'hide-protected' => [],
            'hide-attributes' => '',
            'hide-methods' => 'true',
            'hide-empty-blocks' => null,
        ]);
        $configuration = new DigraphConfiguration($options);

        $this->assertFalse($configuration->searchRecursively());
        $this->assertTrue($configuration->extractAssociations());
        $this->assertTrue($configuration->hidePrivate());
        $this->assertFalse($configuration->hideProtected());
        $this->assertFalse($configuration->hideAttributes());
        $this->assertTrue($configuration->hideMethods());
        $this->assertFalse($configuration->hideEmptyBlocks());
    }

    private function options(array $override)
    {
        return array_merge([
            'recursive' => true,
            'associations' => true,
            'hide-private' => true,
            'hide-protected' => true,
            'hide-public' => true,
            'hide-attributes' => true,
            'hide-methods' => true,
            'hide-empty-blocks' => true,
            'theme' => 'phuml',
        ], $override);
    }
}
