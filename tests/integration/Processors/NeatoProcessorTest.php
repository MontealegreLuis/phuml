<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\ContractTests\ImageProcessorTest;
use Symplify\SmartFileSystem\SmartFileSystem;

final class NeatoProcessorTest extends ImageProcessorTest
{
    /** @test */
    function it_has_a_name()
    {
        $processor = ImageProcessor::neato(new SmartFileSystem());

        $name = $processor->name();

        $this->assertEquals('Neato', $name);
    }

    function processor(): ImageProcessor
    {
        return ImageProcessor::neato(new SmartFileSystem());
    }
}
