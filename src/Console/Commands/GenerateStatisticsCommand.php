<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Console\Commands;

use PhUml\Actions\CanGenerateStatistics;
use PhUml\Actions\GenerateStatistics;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\StatisticsProcessor;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateStatisticsCommand extends Command implements CanGenerateStatistics
{
    /** @var OutputInterface */
    private $output;

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('phuml:statistics')
            ->setDescription('Generate statistics about the code of a given directory')
            ->setHelp(<<<HELP
Example:
    php bin/phuml phuml:statistics -r  ./src statistics.txt

    This example will scan the `./src` directory recursively for php files.
    It will generate the statistics and save them to the file `statistics.txt`.
HELP
            )
            ->addOption(
                'recursive',
                'r',
                InputOption::VALUE_NONE,
                'Look for classes in the given directory recursively'
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to be scanned to generate the class diagram'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your statistics file'
            )
        ;
    }

    /**
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $directory = $input->getArgument('directory');
        $statisticsFile = $input->getArgument('output');
        $recursive = (bool)$input->getOption('recursive');

        if (!is_dir($directory)) {
            throw new RuntimeException("'$directory' is not a valid directory");
        }

        $action = new GenerateStatistics(new TokenParser(), new StatisticsProcessor());
        $action->attach($this);

        $finder = new CodeFinder();
        $finder->addDirectory($directory, $recursive);

        $output->writeln('[|] Running... (This may take some time)');

        $action->generate($finder, $statisticsFile);

        return 0;
    }

    public function runningParser(): void
    {
        $this->output->writeln('[|] Parsing class structure');
    }

    public function runningProcessor(StatisticsProcessor $processor): void
    {
        $this->output->writeln("[|] Running '{$processor->name()}' processor");
    }

    public function savingResult(): void
    {
        $this->output->writeln('[|] Writing generated data to disk');
    }
}
