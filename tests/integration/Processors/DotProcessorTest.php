<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\ContractTests\ImageProcessorTest;
use Symplify\SmartFileSystem\SmartFileSystem;

final class DotProcessorTest extends ImageProcessorTest
{
    /** @test */
    function it_has_a_name()
    {
        $processor = ImageProcessor::dot(new SmartFileSystem());

        $name = $processor->name();

        $this->assertSame('Dot', $name);
    }

    function processor(): ImageProcessor
    {
        return ImageProcessor::dot(new SmartFileSystem());
    }
}
