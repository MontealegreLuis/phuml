<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\SourceCodeFinder;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\BufferedOutput;

final class FindCodeTest extends TestCase
{
    /** @test */
    function it_displays_that_the_process_is_about_to_start()
    {
        $codeFinder = SourceCodeFinder::fromConfiguration(A::codeFinderConfiguration()->build());
        $digraph = new FindCode($codeFinder, $this->display);

        $digraph->__invoke(new CodebaseDirectory(__DIR__));

        $this->assertStringContainsString('Running... (This may take some time)', $this->output->fetch());
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
