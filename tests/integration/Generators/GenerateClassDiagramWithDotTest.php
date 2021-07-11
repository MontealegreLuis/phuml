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
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputFilePath;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;

final class GenerateClassDiagramWithDotTest extends TestCase
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
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot.png';
        $sourceCode = $finder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);

        $this->generator->generate($codebase, $diagram, $display);

        $this->assertImagesSame($expectedDiagram, $diagram->value());
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_a_recursive_finder()
    {
        $display = new ConsoleProgressDisplay(new NullOutput());
        $codeFinder = SourceCodeFinder::recursive();
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-recursive.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-recursive.png';
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);

        $this->generator->generate($codebase, $diagram, $display);

        $this->assertImagesSame($expectedDiagram, $diagram->value());
    }

    /** @before */
    function let()
    {
        $this->generator = new ClassDiagramGenerator(
            new GraphvizProcessor(new ClassGraphBuilder(new EdgesBuilder())),
            ImageProcessor::dot(new Filesystem())
        );
    }

    private ClassDiagramGenerator $generator;
}
