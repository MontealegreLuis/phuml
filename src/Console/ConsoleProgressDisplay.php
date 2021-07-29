<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use PhUml\Processors\Processor;
use PhUml\Stages\ProgressDisplay;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * It provides visual feedback to the use about the progress of the current command
 *
 * @see ProgressDisplay for more details about the things that are reported by this display
 */
final class ConsoleProgressDisplay implements ProgressDisplay
{
    public function __construct(private OutputInterface $output)
    {
    }

    public function start(): void
    {
        $this->display('Running... (This may take some time)');
    }

    public function runningParser(): void
    {
        $this->display('Parsing codebase structure');
    }

    public function runningProcessor(Processor $processor): void
    {
        $this->display("Running '{$processor->name()}' processor");
    }

    public function savingResult(): void
    {
        $this->display('Writing generated data to disk');
    }

    private function display(string $message): void
    {
        $this->output->writeln("<info>[|]</info> $message");
    }
}
