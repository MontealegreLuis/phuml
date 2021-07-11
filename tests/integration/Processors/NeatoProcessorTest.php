<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\ContractTests\ImageProcessorTest;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

final class NeatoProcessorTest extends ImageProcessorTest
{
    /** @test */
    function it_has_a_name()
    {
        $processor = ImageProcessor::neato(new Filesystem());

        $name = $processor->name();

        $this->assertEquals('Neato', $name);
    }

    function processor(Process $process = null): ImageProcessor
    {
        return ImageProcessor::neato(new Filesystem());
    }
}
