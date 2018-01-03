<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use PhUml\ContractTests\ImageProcessorTest;
use Symfony\Component\Process\Process;

class DotProcessorTest extends ImageProcessorTest
{
    /** @test */
    function it_has_a_name()
    {
        $processor = new DotProcessor();

        $name = $processor->name();

        $this->assertEquals('Dot', $name);
    }


    function processor(Process $process = null): ImageProcessor
    {
        return new DotProcessor($process);
    }
}
