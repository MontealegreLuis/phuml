<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use PhUml\Actions\CanExecuteAction;
use PhUml\Processors\Processor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class ProgressDisplay implements CanExecuteAction
{
    /** @var OutputInterface */
    private $output;

    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output ?? new StreamOutput(fopen('php://memory', 'w', false));
    }

    public function runningParser(): void
    {
        $this->output->writeln('[|] Parsing class structure');
    }

    public function runningProcessor(Processor $processor): void
    {
        $this->output->writeln("[|] Running '{$processor->name()}' processor");
    }

    public function savingResult(): void
    {
        $this->output->writeln('[|] Writing generated data to disk');
    }
}
