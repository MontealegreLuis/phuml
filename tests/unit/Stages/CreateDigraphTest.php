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
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\BufferedOutput;

final class CreateDigraphTest extends TestCase
{
    /** @test */
    function it_displays_the_name_of_the_processor_that_will_generate_the_digraph_in_DOT_format()
    {
        $digraph = new CreateDigraph(A::graphvizProcessor()->build(), $this->display);

        $digraph->__invoke(new Codebase());

        $this->assertStringContainsString('Running \'Graphviz\' processor', $this->output->fetch());
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
