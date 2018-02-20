<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\ExternalNumericIdDefinitionsResolver;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdClassDefinitionBuilder;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Fakes\WithNumericIds;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\Php5Parser;
use PhUml\Processors\GraphvizProcessor;

class GenerateDotFileTest extends TestCase
{
    use WithNumericIds, WithDotLanguageAssertions;

    /** @test */
    function it_fails_to_generate_the_dot_file_if_a_command_is_not_provided()
    {
        $generator = new DotFileGenerator(new CodeParser(), new GraphvizProcessor());

        $this->expectException(LogicException::class);

        $generator->generate(new NonRecursiveCodeFinder(), 'wont-be-generated.gv');
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory()
    {
        $file = __DIR__ . '/../resources/.output/dot.gv';
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));

        $this->generator->generate($finder, $file);

        $this->resetIds();
        $digraphInDotFormat = file_get_contents($file);
        $this->assertNode(new NumericIdClass('plBase'), $digraphInDotFormat);
        $this->assertNode(new NumericIdClass('plPhuml'), $digraphInDotFormat);
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory_using_a_recursive_finder()
    {
        $file = __DIR__ . '/../resources/.output/dot.gv';
        $finder = new CodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));

        $this->generator->generate($finder, $file);

        $this->resetIds();
        $base = new NumericIdClass('plBase');
        $tokenParser = new NumericIdClass('plStructureTokenparserGenerator');
        $attribute = new NumericIdClass('plPhpAttribute');
        $class = new NumericIdClass('plPhpClass');
        $function = new NumericIdClass('plPhpFunction');
        $parameter = new NumericIdClass('plPhpFunctionParameter');
        $interface = new NumericIdClass('plPhpInterface');
        $uml = new NumericIdClass('plPhuml');
        $dotProcessor = new NumericIdClass('plDotProcessor');
        $graphvizProcessor = new NumericIdClass('plGraphvizProcessor');
        $graphvizOptions = new NumericIdClass('plGraphvizProcessorOptions');
        $defaultStyle = new NumericIdClass('plGraphvizProcessorDefaultStyle');
        $neatoProcessor = new NumericIdClass('plNeatoProcessor');
        $options = new NumericIdClass('plProcessorOptions');
        $statisticsProcessor = new NumericIdClass('plStatisticsProcessor');
        $structureGenerator = new NumericIdClass('plStructureGenerator');
        $externalCommand = new NumericIdClass('plExternalCommandProcessor');
        $processor = new NumericIdClass('plProcessor');
        $style = new NumericIdClass('plGraphvizProcessorStyle');
        $digraphInDotFormat = file_get_contents($file);
        $this->assertNode($base, $digraphInDotFormat);
        $this->assertNode($structureGenerator, $digraphInDotFormat);
        $this->assertNode($tokenParser, $digraphInDotFormat);
        $this->assertInheritance($tokenParser, $structureGenerator, $digraphInDotFormat);
        $this->assertNode($attribute, $digraphInDotFormat);
        $this->assertNode($class, $digraphInDotFormat);
        $this->assertNode($function, $digraphInDotFormat);
        $this->assertNode($parameter, $digraphInDotFormat);
        $this->assertNode($interface, $digraphInDotFormat);
        $this->assertNode($uml, $digraphInDotFormat);
        $this->assertNode($externalCommand, $digraphInDotFormat);
        $this->assertNode($dotProcessor, $digraphInDotFormat);
        $this->assertInheritance($dotProcessor, $externalCommand, $digraphInDotFormat);
        $this->assertNode($processor, $digraphInDotFormat);
        $this->assertNode($graphvizProcessor, $digraphInDotFormat);
        $this->assertInheritance($graphvizProcessor, $processor, $digraphInDotFormat);
        $this->assertNode($options, $digraphInDotFormat);
        $this->assertNode($graphvizOptions, $digraphInDotFormat);
        $this->assertInheritance($graphvizOptions, $options, $digraphInDotFormat);
        $this->assertNode($style, $digraphInDotFormat);
        $this->assertNode($defaultStyle, $digraphInDotFormat);
        $this->assertInheritance($defaultStyle, $style, $digraphInDotFormat);
        $this->assertNode($neatoProcessor, $digraphInDotFormat);
        $this->assertInheritance($neatoProcessor, $externalCommand, $digraphInDotFormat);
        $this->assertNode($statisticsProcessor, $digraphInDotFormat);
        $this->assertInheritance($statisticsProcessor, $processor, $digraphInDotFormat);
    }

    /** @before */
    function createGenerator()
    {
        $this->generator = new DotFileGenerator(
            new CodeParser(
                new Php5Parser(new NumericIdClassDefinitionBuilder()),
                new ExternalNumericIdDefinitionsResolver()
            ),
            new GraphvizProcessor()
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    /** @var DotFileGenerator */
    private $generator;
}
