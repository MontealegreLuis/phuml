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

    /** @test */
    function it_knows_it_is_a_valid_initial_processor()
    {
        $processor = new StatisticsProcessor();

        $isInitial = $processor->isInitial();

        $this->assertTrue($isInitial);
    }
}
