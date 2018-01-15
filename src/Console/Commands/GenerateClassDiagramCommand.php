<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Actions\GenerateClassDiagram;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;
use RuntimeException;
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
 * There are 3 options
 *
 * 1. `processor`. The command to be used to create the `png` image, it can be either `neato` or `dot`
 *    This is the only required option
 * 2. `recursive`. If present it will look recursively within the `directory` provided
 * 3. `associations`. If present the command will generate associations to the classes/interfaces
 *    injected through the constructor and the attributes of the class
 */
class GenerateClassDiagramCommand extends GeneratorCommand
{
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

    This example will scan the `./src` directory recursively for php files.
    It will process them with the option `associations` set to true. After that it 
    will be send to the `neato` processor and saved to the file `out.png`.
HELP
            )
            ->addOption(
                'recursive',
                'r',
                InputOption::VALUE_NONE,
                'Look for classes in the given directory recursively'
            )
            ->addOption(
                'processor',
                'p',
                InputOption::VALUE_REQUIRED,
                'Choose between the neato and dot processors'
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
                'The directory to be scanned to generate the class diagram'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your class diagram'
            );
    }

    /**
     * @throws \LogicException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        $diagramFile = $input->getArgument('output');
        $recursive = (bool)$input->getOption('recursive');
        $associations = (bool)$input->getOption('associations');
        $processor = $input->getOption('processor');

        if (!is_dir($directory)) {
            throw new RuntimeException("'$directory' is not a valid directory");
        }
        $finder = new CodeFinder();
        $finder->addDirectory($directory, $recursive);

        $dotProcessor = new GraphvizProcessor();
        if ($associations) {
            $dotProcessor->createAssociations();
        }

        $action = new GenerateClassDiagram(new TokenParser(), $dotProcessor);
        $action->attach($this->display);

        if (!\in_array($processor, ['neato', 'dot'], true)) {
            throw new RuntimeException("Expected processors are neato and dot, '$processor' found");
        }

        if ($processor === 'dot') {
            $action->setImageProcessor(new DotProcessor());
        } else {
            $action->setImageProcessor(new NeatoProcessor());
        }

        $output->writeln('[|] Running... (This may take some time)');

        $action->generate($finder, $diagramFile);

        return 0;
    }
}
