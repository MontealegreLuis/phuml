<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputFilePath;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

final class GenerateDotFileTest extends TestCase
{
    use WithDotLanguageAssertions;

    /** @test */
    function it_creates_the_dot_file_of_a_directory()
    {
        $finder = SourceCodeFinder::nonRecursive();
        $sourceCode = $finder->find($this->codebaseDirectory);
        $codebase = $this->parser->parse($sourceCode);

        $this->generator->generate($codebase, $this->display);

        $digraphInDotFormat = file_get_contents($this->pathToDotFile->value());
        $this->assertNode(A::classNamed('plBase'), $digraphInDotFormat);
        $this->assertNode(A::classNamed('plPhuml'), $digraphInDotFormat);
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory_using_a_recursive_finder()
    {
        $finder = SourceCodeFinder::recursive();
        $sourceCode = $finder->find($this->codebaseDirectory);
        $codebase = $this->parser->parse($sourceCode);

        $this->generator->generate($codebase, $this->display);

        $base = A::classNamed('plBase');
        $tokenParser = A::classNamed('plStructureTokenparserGenerator');
        $attribute = A::classNamed('plPhpAttribute');
        $class = A::classNamed('plPhpClass');
        $function = A::classNamed('plPhpFunction');
        $parameter = A::classNamed('plPhpFunctionParameter');
        $interface = A::classNamed('plPhpInterface');
        $uml = A::classNamed('plPhuml');
        $dotProcessor = A::classNamed('plDotProcessor');
        $graphvizProcessor = A::classNamed('plGraphvizProcessor');
        $styleName = A::classNamed('plStyleName');
        $graphvizOptions = A::classNamed('plGraphvizProcessorOptions');
        $defaultStyle = A::classNamed('plGraphvizProcessorDefaultStyle');
        $neatoProcessor = A::classNamed('plNeatoProcessor');
        $options = A::classNamed('plProcessorOptions');
        $statisticsProcessor = A::classNamed('plStatisticsProcessor');
        $structureGenerator = A::classNamed('plStructureGenerator');
        $externalCommand = A::classNamed('plExternalCommandProcessor');
        $processor = A::classNamed('plProcessor');
        $style = A::classNamed('plGraphvizProcessorStyle');
        $digraphInDotFormat = file_get_contents($this->pathToDotFile->value());
        $this->assertNode($base, $digraphInDotFormat);
        $this->assertNode($structureGenerator, $digraphInDotFormat);
        $this->assertNode($styleName, $digraphInDotFormat);
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
        $this->codebaseDirectory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes');
        $this->pathToDotFile = new OutputFilePath(__DIR__ . '/../../resources/.output/dot.gv');
        $this->generator = new DotFileGenerator(new GraphvizProcessor());
        $this->parser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $this->display = new ConsoleProgressDisplay(new NullOutput());
    }

    private DotFileGenerator $generator;

    private OutputFilePath $pathToDotFile;

    private ProgressDisplay $display;

    private CodebaseDirectory $codebaseDirectory;

    private CodeParser $parser;
}
