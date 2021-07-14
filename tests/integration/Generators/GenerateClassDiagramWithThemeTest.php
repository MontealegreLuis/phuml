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

final class GenerateClassDiagramWithThemeTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_php_theme()
    {
        $codeFinder = SourceCodeFinder::recursive();
        $diagramPath = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-php-theme.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-php-theme.png';
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);
        $configuration = A::digraphConfiguration()
            ->withTheme('php')
            ->withAssociations()
            ->withoutEmptyBlocks()
            ->build();
        $graphvizProcessor = GraphvizProcessor::fromConfiguration($configuration);
        $digraph = $graphvizProcessor->process($codebase);

        $diagram = $this->generator->generate($digraph, $this->display);

        $this->outputWriter->save($diagram, $diagramPath);
        $this->assertImagesSame($expectedDiagram, $diagramPath->value());
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_classic_theme()
    {
        $codeFinder = SourceCodeFinder::recursive();
        $diagramPath = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-classic-theme.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-classic-theme.png';
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);
        $configuration = A::digraphConfiguration()
            ->withTheme('classic')
            ->withAssociations()
            ->withoutEmptyBlocks()
            ->build();
        $graphvizProcessor = GraphvizProcessor::fromConfiguration($configuration);
        $digraph = $graphvizProcessor->process($codebase);

        $diagram = $this->generator->generate($digraph, $this->display);

        $this->outputWriter->save($diagram, $diagramPath);
        $this->assertImagesSame($expectedDiagram, $diagramPath->value());
    }

    /** @before */
    function let()
    {
        $this->generator = new ClassDiagramGenerator(ImageProcessor::dot(new SmartFileSystem()));
        $this->display = new ConsoleProgressDisplay(new NullOutput());
        $this->outputWriter = new OutputWriter(new SmartFileSystem());
    }

    private ConsoleProgressDisplay $display;

    private OutputWriter $outputWriter;

    private ClassDiagramGenerator $generator;
}
