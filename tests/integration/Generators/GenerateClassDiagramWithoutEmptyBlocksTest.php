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

final class GenerateClassDiagramWithoutEmptyBlocksTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_removes_empty_blocks_if_only_definition_names_are_shown()
    {
        $finder = SourceCodeFinder::nonRecursive();
        $diagramPath = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-without-empty-blocks.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-without-empty-blocks.png';
        $sourceCode = $finder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes'));
        $codebase = $this->parser->parse($sourceCode);
        $configuration = A::digraphConfiguration()->withoutEmptyBlocks()->build();
        $graphvizProcessor = GraphvizProcessor::fromConfiguration($configuration);
        $digraph = $graphvizProcessor->process($codebase);

        $diagram = $this->generator->generate($digraph, $this->display);

        $this->outputWriter->save($diagram, $diagramPath);
        $this->assertImagesSame($expectedDiagram, $diagramPath->value());
    }

    /** @before*/
    function let()
    {
        $configuration = A::codeParserConfiguration()->withoutAttributes()->withoutMethods()->build();
        $this->parser = CodeParser::fromConfiguration($configuration);
        $this->generator = new ClassDiagramGenerator(ImageProcessor::dot(new SmartFileSystem()));
        $this->display = new ConsoleProgressDisplay(new NullOutput());
        $this->outputWriter = new OutputWriter(new SmartFileSystem());
    }

    private ClassDiagramGenerator $generator;

    private ConsoleProgressDisplay $display;

    private CodeParser $parser;

    private OutputWriter $outputWriter;
}
