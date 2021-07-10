<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Fakes\ExternalNumericIdDefinitionsResolver;
use PhUml\Fakes\NumericIdClassDefinitionBuilder;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Fakes\WithNumericIds;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputFilePath;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

final class GenerateDotFileTest extends TestCase
{
    use WithNumericIds;
    use WithDotLanguageAssertions;

    /** @test */
    function it_creates_the_dot_file_of_a_directory()
    {
        $finder = SourceCodeFinder::nonRecursive();
        $sourceCode = $finder->find(new CodebaseDirectory($this->pathToCode));

        $this->generator->generate($sourceCode, $this->pathToDotFile, $this->display);

        $this->resetIds();
        $digraphInDotFormat = file_get_contents($this->pathToDotFile->value());
        $this->assertNode(A::numericIdClassNamed('plBase'), $digraphInDotFormat);
        $this->assertNode(A::numericIdClassNamed('plPhuml'), $digraphInDotFormat);
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory_using_a_recursive_finder()
    {
        $finder = SourceCodeFinder::recursive();
        $sourceCode = $finder->find(new CodebaseDirectory($this->pathToCode));

        $this->generator->generate($sourceCode, $this->pathToDotFile, $this->display);

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
        $styleName = A::numericIdClassNamed('plStyleName');
        $graphvizOptions = A::numericIdClassNamed('plGraphvizProcessorOptions');
        $defaultStyle = A::numericIdClassNamed('plGraphvizProcessorDefaultStyle');
        $neatoProcessor = A::numericIdClassNamed('plNeatoProcessor');
        $options = A::numericIdClassNamed('plProcessorOptions');
        $statisticsProcessor = A::numericIdClassNamed('plStatisticsProcessor');
        $structureGenerator = A::numericIdClassNamed('plStructureGenerator');
        $externalCommand = A::numericIdClassNamed('plExternalCommandProcessor');
        $processor = A::numericIdClassNamed('plProcessor');
        $style = A::numericIdClassNamed('plGraphvizProcessorStyle');
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
        $this->pathToCode = __DIR__ . '/../../resources/.code/classes';
        $this->pathToDotFile = new OutputFilePath(__DIR__ . '/../../resources/.output/dot.gv');
        $this->generator = new DotFileGenerator(
            new CodeParser(
                new PhpCodeParser(new NumericIdClassDefinitionBuilder()),
                [new ExternalNumericIdDefinitionsResolver()]
            ),
            new GraphvizProcessor()
        );
        $this->display = new ConsoleProgressDisplay(new NullOutput());
    }

    private ?DotFileGenerator $generator = null;

    private ?OutputFilePath $pathToDotFile = null;

    private ?ProgressDisplay $display = null;

    private ?string $pathToCode = null;
}
