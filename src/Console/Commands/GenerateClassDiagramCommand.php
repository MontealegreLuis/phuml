<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use InvalidArgumentException;
use PhUml\Configuration\ClassDiagramConfiguration;
use PhUml\Configuration\DigraphBuilder;
use PhUml\Configuration\DigraphConfiguration;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Generators\ClassDiagramGenerator;
use PhUml\Parser\CodeParser;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\SmartFileSystem\SmartFileSystem;

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
        $generatorInput = new GeneratorInput($input->getArguments(), $input->getOptions());
        $codebaseDirectory = $generatorInput->directory();
        $classDiagramPath = $generatorInput->outputFile();

        $configuration = new ClassDiagramConfiguration($generatorInput->options());
        $digraphConfiguration = new DigraphConfiguration($generatorInput->options());
        $builder = new DigraphBuilder($digraphConfiguration);
        $codeFinder = $builder->codeFinder();
        $parser = CodeParser::fromConfiguration($generatorInput->codeParserConfiguration());
        $filesystem = new SmartFileSystem();
        $writer = new OutputWriter($filesystem);
        $imageProcessor = $configuration->isDotProcessor()
            ? ImageProcessor::dot($filesystem)
            : ImageProcessor::neato($filesystem);
        $digraphProcessor = GraphvizProcessor::fromConfiguration($digraphConfiguration);
        $classDiagramGenerator = new ClassDiagramGenerator($imageProcessor);
        $display = new ConsoleProgressDisplay($output);

        $display->start();
        $sourceCode = $codeFinder->find($codebaseDirectory);
        $display->runningParser();
        $codebase = $parser->parse($sourceCode);
        $display->runningProcessor($digraphProcessor);
        $digraph = $digraphProcessor->process($codebase);
        $classDiagram = $classDiagramGenerator->generate($digraph, $display);
        $display->savingResult();
        $writer->save($classDiagram, $classDiagramPath);

        return self::SUCCESS;
    }
}
