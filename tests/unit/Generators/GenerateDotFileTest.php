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
use PhUml\Fakes\NumericIdClassDefinitionBuilder;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Fakes\WithNumericIds;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\TestBuilders\A;

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
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from($this->pathToCode));

        $this->generator->generate($finder, $this->pathToDotFile);

        $this->resetIds();
        $digraphInDotFormat = file_get_contents($this->pathToDotFile);
        $this->assertNode(A::numericIdClassNamed('plBase'), $digraphInDotFormat);
        $this->assertNode(A::numericIdClassNamed('plPhuml'), $digraphInDotFormat);
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory_using_a_recursive_finder()
    {
        $finder = new CodeFinder();
        $finder->addDirectory(CodebaseDirectory::from($this->pathToCode));

        $this->generator->generate($finder, $this->pathToDotFile);

        $this->resetIds();
        $base = A::numericIdClassNamed('plBase');
        $tokenParser = A::numericIdClassNamed('plStructureTokenparserGenerator');
        $attribute = A::numericIdClassNamed('plPhpAttribute');
        $class = A::numericIdClassNamed('plPhpClass');
        $function = A::numericIdClassNamed('plPhpFunction');
        $parameter = A::numericIdClassNamed('plPhpFunctionParameter');
        $interface = A::numericIdClassNamed('plPhpInterface');
        $uml = A::numericIdClassNamed('plPhuml');
        $dotProcessor = A::numericIdClassNamed('plDotProcessor');
        $graphvizProcessor = A::numericIdClassNamed('plGraphvizProcessor');
        $graphvizOptions = A::numericIdClassNamed('plGraphvizProcessorOptions');
        $defaultStyle = A::numericIdClassNamed('plGraphvizProcessorDefaultStyle');
        $neatoProcessor = A::numericIdClassNamed('plNeatoProcessor');
        $options = A::numericIdClassNamed('plProcessorOptions');
        $statisticsProcessor = A::numericIdClassNamed('plStatisticsProcessor');
        $structureGenerator = A::numericIdClassNamed('plStructureGenerator');
        $externalCommand = A::numericIdClassNamed('plExternalCommandProcessor');
        $processor = A::numericIdClassNamed('plProcessor');
        $style = A::numericIdClassNamed('plGraphvizProcessorStyle');
        $digraphInDotFormat = file_get_contents($this->pathToDotFile);
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
    function let()
    {
        $this->pathToCode = __DIR__ . '/../../resources/.code/classes';
        $this->pathToDotFile = __DIR__ . '/../../resources/.output/dot.gv';
        $this->generator = new DotFileGenerator(
            new CodeParser(
                new PhpCodeParser(new NumericIdClassDefinitionBuilder()),
                new ExternalNumericIdDefinitionsResolver()
            ),
            new GraphvizProcessor()
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    /** @var DotFileGenerator */
    private $generator;

    /** @var string */
    private $pathToDotFile;

    /** @var string */
    private $pathToCode;
}
