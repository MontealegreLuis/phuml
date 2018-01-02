<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plDotProcessorTest extends TestCase 
{
    /** @test */
    function it_knows_its_an_invalid_initial_processor()
    {
        $processor = new plDotProcessor();

        $isInitial = $processor->isInitial();

        $this->assertFalse($isInitial);
    }
}
