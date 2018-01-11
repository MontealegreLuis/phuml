<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Actions\GenerateDotFile;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\GraphvizProcessor;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDotFileCommand extends GeneratorCommand
{
    protected function configure()
    {
        $this
            ->setName('phuml:dot')
            ->setDescription('Generates a digraph in DOT format of a given directory')
            ->setHelp(<<<HELP
Example:
    php bin/phuml phuml:dot -r -a ./src dot.gv

    This example will scan the `./src` directory recursively for php files.
    It will process them with the option `associations` set to true.
    It will generate a digraph in dot format and save it to the file `dot.gv`.
HELP
            )
            ->addOption(
                'recursive',
                'r',
                InputOption::VALUE_NONE,
                'Look for classes in the given directory recursively'
            )
            ->addOption(
                'associations',
                'a',
                InputOption::VALUE_NONE,
                'If present, the Graphviz processor will generate association among classes'
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to be scanned to generate the dot file'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your dot file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        $dotFile = $input->getArgument('output');
        $associations = (bool)$input->getOption('associations');
        $recursive = (bool)$input->getOption('recursive');

        if (!is_dir($directory)) {
            throw new RuntimeException("'$directory' is not a valid directory");
        }

        $action = new GenerateDotFile(new TokenParser(), new GraphvizProcessor($associations));
        $action->attach($this->display);

        $finder = new CodeFinder();
        $finder->addDirectory($directory, $recursive);

        $output->writeln('[|] Running... (This may take some time)');

        $action->generate($finder, $dotFile);

        return 0;
    }
}
