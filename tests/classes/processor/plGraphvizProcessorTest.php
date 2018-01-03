<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plGraphvizProcessorTest extends TestCase 
{
    /** @test */
    function it_has_a_name()
    {
        $processor = new plGraphvizProcessor();

        $name = $processor->name();

        $this->assertEquals('Graphviz', $name);
    }

    /** @test */
    function it_knows_its_a_valid_initial_processor()
    {
        $processor = new plGraphvizProcessor();

        $isInitial = $processor->isInitial();

        $this->assertTrue($isInitial);
    }
}
