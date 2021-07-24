<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Stages;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Processors\OutputContent;
use PhUml\Processors\OutputFilePath;
use PhUml\Processors\OutputWriter;
use Symfony\Component\Console\Output\BufferedOutput;
use Symplify\SmartFileSystem\SmartFileSystem;

final class SaveFileTest extends TestCase
{
    /** @test */
    function it_displays_that_a_file_is_about_to_be_saved()
    {
        $statistics = new SaveFile(new OutputWriter(new SmartFileSystem()), $this->display);
        $content = new OutputContent('Any content is allowed here');
        $path = OutputFilePath::withExpectedExtension($this->filePath, 'txt');

        $statistics->saveTo($content, $path);

        $this->assertStringContainsString('Writing generated data to disk', $this->output->fetch());
    }

    /** @before  */
    function let()
    {
        $this->filePath = __DIR__ . '/../../resources/.output/dummy.txt';
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
        $this->output = new BufferedOutput();
        $this->display = new ConsoleProgressDisplay($this->output);
    }

    private ProgressDisplay $display;

    private BufferedOutput $output;

    private string $filePath;
}
