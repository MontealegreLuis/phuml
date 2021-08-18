<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCode;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\BufferedOutput;

final class ParseCodeTest extends TestCase
{
    /** @test */
    function it_displays_that_code_is_about_to_be_parsed()
    {
        $digraph = new ParseCode(CodeParser::fromConfiguration(A::codeParserConfiguration()->build()), $this->display);

        $digraph->__invoke(new SourceCode());

        $this->assertStringContainsString('Parsing codebase structure', $this->output->fetch());
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
