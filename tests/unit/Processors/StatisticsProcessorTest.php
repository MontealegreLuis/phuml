<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;

class StatisticsProcessorTest extends TestCase
{
    /** @test */
    function it_has_a_name()
    {
        $processor = new StatisticsProcessor();

        $name = $processor->name();

        $this->assertEquals('Statistics', $name);
    }
}
