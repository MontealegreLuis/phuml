<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;
use PhUml\Templates\TemplateEngine;

final class StatisticsProcessorTest extends TestCase
{
    /** @test */
    function it_has_a_name()
    {
        $processor = new StatisticsProcessor(new TemplateEngine());

        $name = $processor->name();

        $this->assertEquals('Statistics', $name);
    }
}
