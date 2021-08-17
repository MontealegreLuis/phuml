<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PHPUnit\Framework\TestCase;
use PhUml\Processors\ImageGenerationFailure;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputContent;
use Symfony\Component\Finder\Finder;

abstract class ImageProcessorTest extends TestCase
{
    abstract function processor(): ImageProcessor;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_an_image_from_a_dot_file()
    {
        $finder = (new Finder())->contains('phuml')->in(sys_get_temp_dir());
        foreach ($finder as $file) {
            unlink($file->getRealPath());
        }
        $digraph = new OutputContent((string) file_get_contents(__DIR__ . '/../../resources/.fixtures/classes.dot'));
        $name = strtolower($this->processor()->name());
        $expectedImage = __DIR__ . "/../../resources/.fixtures/${name}.png";

        $pngDiagram = $this->processor()->process($digraph);

        $this->assertEquals($pngDiagram->value(), file_get_contents($expectedImage));
        $this->assertEquals(0, (new Finder())->contains('phuml')->in(sys_get_temp_dir())->count());
    }

    /** @test */
    function it_provides_feedback_when_the_call_to_the_command_fails()
    {
        $this->expectException(ImageGenerationFailure::class);
        $this->expectExceptionMessageMatches('/syntax error in line 1 near/');

        $this->processor()->process(new OutputContent('invalid dot content'));
    }
}
