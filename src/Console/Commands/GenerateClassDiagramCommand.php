<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Configuration\ClassDiagramBuilder;
use PhUml\Configuration\ClassDiagramConfiguration;
use PhUml\Parser\CodebaseDirectory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command will generate a UML class diagram by reading an OO codebase
 *
 * This command has 2 required arguments
 *
 * 1. `directory`. The path where your codebase lives
 * 2. `output`. The path to where the generated `png` image will be saved
 *
 * There is 1 option specific to this command
 *
 * 1. `processor`. The command to be used to create the `png` image, it can be either `neato` or `dot`
 *    This is the only required option
 *
 * @see WithDigraphConfiguration::addDigraphOptions() for more details about the rest of the options
 */
class GenerateClassDiagramCommand extends GeneratorCommand
{
    use WithDigraphConfiguration;

    /** @throws \InvalidArgumentException */
    protected function configure()
    {
        $this
            ->setName('phuml:diagram')
            ->setDescription('Generate a class diagram scanning the given directory')
            ->setHelp(
                <<<HELP
Example:
    php bin/phuml phuml:diagram -r -a -p neato ./src out.png

    This command will look for PHP files within the `./src` directory and its sub-directories.
    It will extract associations from constructor parameters and attributes. 
    It will generate the class diagram using the `neato` processor 
    It will save the diagram to the file `out.png`.
HELP
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to be scanned to generate the class diagram'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your class diagram'
            )
            ->addOption(
                'processor',
                'p',
                InputOption::VALUE_REQUIRED,
                'Choose between the neato and dot processors'
            )
        ;
        $this->addDigraphOptions($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codebasePath = $input->getArgument('directory');
        $classDiagramPath = $input->getArgument('output');

        $builder = new ClassDiagramBuilder(new ClassDiagramConfiguration($input->getOptions()));

        $codeFinder = $builder->codeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from($codebasePath));

        $classDiagramGenerator = $builder->classDiagramGenerator();
        $classDiagramGenerator->attach($this->display);

        $classDiagramGenerator->generate($codeFinder, $classDiagramPath);

        return 0;
    }
}
