<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PHPUnit\Framework\TestCase;
use PhUml\Generators\ClassDiagramConfiguration;
use PhUml\Processors\UnknownImageProcessor;

final class ClassDiagramConfigurationTest extends TestCase
{
    /** @test */
    function it_fails_to_set_an_invalid_image_processor()
    {
        $this->expectException(UnknownImageProcessor::class);
        $this->expectExceptionMessage(
            'Invalid processor "not-a-valid-image-processor-name" found, expected processors are: neato, dot'
        );

        new ClassDiagramConfiguration($this->options([
            'processor' => 'not-a-valid-image-processor-name',
        ]));
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
