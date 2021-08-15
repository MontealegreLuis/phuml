<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputContent;
use Symfony\Component\Console\Output\BufferedOutput;
use Symplify\SmartFileSystem\SmartFileSystem;

final class CreateClassDiagramTest extends TestCase
{
    /** @test */
    function it_displays_the_name_of_the_graphviz_command_that_will_generate_the_class_diagram()
    {
        $digraph = <<<DIGRAPH
digraph "0462e83f8a8cd8475fdddfbde3e2f5fed899bd81" {
splines = true;
overlap = false;
mindist = 0.6;
}
DIGRAPH;
        $diagram =new CreateClassDiagram(ImageProcessor::dot(new SmartFileSystem()), $this->display);

        $diagram->__invoke(new OutputContent($digraph));

        $this->assertStringContainsString('Running \'Dot\' processor', $this->output->fetch());
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
