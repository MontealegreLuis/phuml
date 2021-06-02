<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Styles\UnknownTheme;

class DigraphConfigurationTest extends TestCase
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

        $this->assertEquals('php', $configuration->theme());
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
