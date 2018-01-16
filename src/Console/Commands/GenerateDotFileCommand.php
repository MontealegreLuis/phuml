<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Actions\GenerateDotFile;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command will generate file in `DOT` format that is ready to use to generate a UML class
 * diagram using either `neato` or `dot`
 *
 * This command has 2 required arguments
 *
 * 1. `directory`. The path where your codebase lives
 * 2. `output`. The path to where the generated `gv` file will be saved
 *
 * There are 2 options
 *
 * 1. `recursive`. If present it will look recursively within the `directory` provided
 * 2. `associations`. If present the command will generate associations to the classes/interfaces
 *    injected through the constructor and the attributes of the class
 */
class GenerateDotFileCommand extends GeneratorCommand
{
    protected function configure()
    {
        $this
            ->setName('phuml:dot')
            ->setDescription('Generates a digraph in DOT format of a given directory')
            ->setHelp(
                <<<HELP
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

        $action = new GenerateDotFile(new CodeParser(), new GraphvizProcessor());
        $action->attach($this->display);

        $finder = new CodeFinder();
        $finder->addDirectory($directory, $recursive);

        $output->writeln('[|] Running... (This may take some time)');

        $action->generate($finder, $dotFile);

        return 0;
    }
}
