<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Styles\UnknownTheme;
use PhUml\Processors\GraphvizConfiguration;

final class GraphvizConfigurationTest extends TestCase
{
    /** @test */
    function it_expects_theme_name_to_be_a_string()
    {
        $this->options['theme'] = 1;
        $this->expectException(InvalidArgumentException::class);

        new GraphvizConfiguration($this->options);
    }

    /** @test */
    function it_fails_to_set_an_invalid_theme_name()
    {
        $this->options['theme'] = 'not-a-valid-theme-name';
        $this->expectException(UnknownTheme::class);

        new GraphvizConfiguration($this->options);
    }

    /** @test */
    function it_expects_the_generate_digraph_associations_option_to_be_a_boolean()
    {
        $this->options['associations'] = '0';
        $this->expectException(InvalidArgumentException::class);

        new GraphvizConfiguration($this->options);
    }

    /** @test */
    function it_expects_the_hide_empty_blocks_option_to_be_a_boolean()
    {
        $this->options['hide-empty-blocks'] = [];
        $this->expectException(InvalidArgumentException::class);

        new GraphvizConfiguration($this->options);
    }

    /** @before */
    function let()
    {
        $this->options = [
            'associations' => true,
            'hide-empty-blocks' => true,
            'theme' => 'phuml',
        ];
    }

    /** @var mixed[]  */
    private array $options;
}
