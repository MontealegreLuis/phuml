<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PHPUnit\Framework\TestCase;
use PhUml\Processors\UnknownImageProcessor;

class ClassDiagramConfigurationTest extends TestCase
{
    /** @test */
    function it_fails_to_set_an_invalid_image_processor()
    {
        $this->expectException(UnknownImageProcessor::class);

        new ClassDiagramConfiguration($this->options([
            'processor' => 'not-a-valid-image-processor-name',
        ]));
    }

    /** @test */
    function it_knows_it_is_the_dot_processor()
    {
        $configuration = new ClassDiagramConfiguration($this->options([
            'processor' => 'dot',
        ]));

        $this->assertTrue($configuration->isDotProcessor());
    }

    /** @test */
    function it_knows_it_is_the_neato_processor()
    {
        $configuration = new ClassDiagramConfiguration($this->options([
            'processor' => 'neato',
        ]));

        $this->assertFalse($configuration->isDotProcessor());
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
