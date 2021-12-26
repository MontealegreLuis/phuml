<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use InvalidArgumentException;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Generators\ClassDiagramConfiguration;
use PhUml\Generators\ClassDiagramGenerator;
use Symfony\Component\Console\Command\Command;
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
final class GenerateClassDiagramCommand extends Command
{
    use WithDigraphConfiguration;

    /** @throws InvalidArgumentException */
    protected function configure(): void
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var array{
         *     processor: string,
         *     recursive: bool,
         *     associations: bool,
         *     "hide-private": bool,
         *     "hide-protected": bool,
         *     "hide-methods": bool,
         *     "hide-attributes": bool,
         *     "hide-empty-blocks": bool,
         *     theme: string
         *  } $options
         */
        $options = $input->getOptions();
        $configuration = new ClassDiagramConfiguration($options, new ConsoleProgressDisplay($output));
        $generator = ClassDiagramGenerator::fromConfiguration($configuration);

        /**
         * @var array{
         *      directory: string,
         *      output: string
         * } $arguments
         */
        $arguments = $input->getArguments();
        $generator->generate(GeneratorInput::pngFile($arguments));

        return self::SUCCESS;
    }
}
