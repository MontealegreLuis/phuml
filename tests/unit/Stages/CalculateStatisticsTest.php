<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Processors\StatisticsProcessor;
use PhUml\Templates\TemplateEngine;
use Symfony\Component\Console\Output\BufferedOutput;

final class CalculateStatisticsTest extends TestCase
{
    /** @test */
    function it_displays_that_statistics_are_about_to_be_calculated()
    {
        $statistics = new CalculateStatistics(new StatisticsProcessor(new TemplateEngine()), $this->display);

        $statistics->__invoke(new Codebase());

        $this->assertStringContainsString('Running \'Statistics\' processor', $this->output->fetch());
    }

    /** @before  */
    function let()
    {
        $this->output = new BufferedOutput();
        $this->display = new ConsoleProgressDisplay($this->output);
    }

    private ProgressDisplay $display;

    private BufferedOutput $output;
}
