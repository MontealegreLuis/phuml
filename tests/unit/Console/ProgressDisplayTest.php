<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use PHPUnit\Framework\TestCase;
use PhUml\Processors\Processor;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Output\BufferedOutput;

final class ProgressDisplayTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_displays_start_message()
    {
        $this->display->start();

        $this->assertStringContainsString('Running... (This may take some time)', $this->output->fetch());
    }

    /** @test */
    function it_displays_running_parser_message()
    {
        $this->display->runningParser();

        $this->assertStringContainsString('Parsing codebase structure', $this->output->fetch());
    }

    /** @test */
    function it_displays_running_processor_message()
    {
        $processor = $this->prophesize(Processor::class);
        $processor->name()->willReturn('neato');

        $this->display->runningProcessor($processor->reveal());

        $this->assertStringContainsString('Running \'neato\' processor', $this->output->fetch());
    }

    /** @test */
    function it_displays_saving_result_message()
    {
        $this->display->savingResult();

        $this->assertStringContainsString('Writing generated data to disk', $this->output->fetch());
    }

    /** @before */
    public function let()
    {
        $this->output = new BufferedOutput();
        $this->display = new ConsoleProgressDisplay($this->output);
    }

    private BufferedOutput $output;

    private ConsoleProgressDisplay $display;
}
