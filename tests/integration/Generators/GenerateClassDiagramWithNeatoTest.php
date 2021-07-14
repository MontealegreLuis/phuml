<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputFilePath;
use PhUml\Processors\OutputWriter;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;
use Symplify\SmartFileSystem\SmartFileSystem;

final class GenerateClassDiagramWithNeatoTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $display = new ConsoleProgressDisplay(new NullOutput());
        $finder = SourceCodeFinder::nonRecursive();
        $diagramPath = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-neato.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato.png';
        $sourceCode = $finder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);
        $graphvizProcessor = GraphvizProcessor::fromConfiguration(A::digraphConfiguration()->build());
        $digraph = $graphvizProcessor->process($codebase);

        $diagram = $this->generator->generate($digraph, $display);

        $this->outputWriter->save($diagram, $diagramPath);
        $this->assertImagesSame($expectedDiagram, $diagramPath->value());
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_a_recursive_finder()
    {
        $display = new ConsoleProgressDisplay(new NullOutput());
        $codeFinder = SourceCodeFinder::recursive();
        $diagramPath = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-neato-recursive.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato-recursive.png';
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);
        $configuration = A::digraphConfiguration()->withAssociations()->build();
        $graphvizProcessor = GraphvizProcessor::fromConfiguration($configuration);
        $digraph = $graphvizProcessor->process($codebase);

        $diagram = $this->generator->generate($digraph, $display);

        $this->outputWriter->save($diagram, $diagramPath);
        $this->assertImagesSame($expectedDiagram, $diagramPath->value());
    }

    /** @before */
    function let()
    {
        $this->generator = new ClassDiagramGenerator(ImageProcessor::neato(new SmartFileSystem()));
        $this->outputWriter = new OutputWriter(new SmartFileSystem());
    }

    private ClassDiagramGenerator $generator;

    private OutputWriter $outputWriter;
}
