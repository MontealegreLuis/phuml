<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use PhUml\Configuration\DigraphBuilder;
use PhUml\Configuration\DigraphConfiguration;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Generators\DotFileGenerator;
use PhUml\Parser\CodeParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
 * @see WithDigraphConfiguration::addDigraphOptions() for more details about all the available options
 */
final class GenerateDotFileCommand extends Command
{
    use WithDigraphConfiguration;

    protected function configure(): void
    {
        $this
            ->setName('phuml:dot')
            ->setDescription('Generates a digraph in DOT format of a given directory')
            ->setHelp(
                <<<HELP
Example:
    php bin/phuml phuml:dot -r -a ./src dot.gv

    This command will look for PHP files within the `./src` directory and its sub-directories.
    It will extract associations from constructor parameters and attributes.
    It will generate a digraph in DOT format and save it to the file `dot.gv`.
HELP
            )
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'The directory to be scanned to generate the DOT file'
            )
            ->addArgument(
                'output',
                InputArgument::REQUIRED,
                'The file name for your DOT file'
            )
        ;
        $this->addDigraphOptions($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $generatorInput = new GeneratorInput($input->getArguments(), $input->getOptions());
        $codebasePath = $generatorInput->directory();
        $dotFilePath = $generatorInput->outputFile();

        $builder = new DigraphBuilder(new DigraphConfiguration($generatorInput->options()));

        $parser = CodeParser::fromConfiguration($generatorInput->codeParserConfiguration());
        $dotFileGenerator = new DotFileGenerator($builder->digraphProcessor());
        $display = new ConsoleProgressDisplay($output);

        $display->start();
        $codeFinder = $builder->codeFinder();
        $sourceCode = $codeFinder->find($codebasePath);
        $display->runningParser();
        $codebase = $parser->parse($sourceCode);

        $dotFileGenerator->generate($codebase, $dotFilePath, $display);

        return self::SUCCESS;
    }
}
