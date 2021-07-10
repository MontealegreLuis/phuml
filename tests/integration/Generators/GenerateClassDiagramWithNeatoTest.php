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
use PhUml\Parser\Code\ExternalDefinitionsResolver;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;
use PhUml\Processors\OutputFilePath;
use Symfony\Component\Console\Output\NullOutput;

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
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-neato.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato.png';

        $this->generator->generate($finder, $diagram, $display);

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
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-neato-recursive.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato-recursive.png';

        $this->generator->generate($codeFinder, $diagram, $display);

        $this->assertImagesSame($expectedDiagram, $diagram->value());
    }

    /** @before */
    function let()
    {
        $this->generator = new ClassDiagramGenerator(
            new CodeParser(new PhpCodeParser(), [new ExternalDefinitionsResolver()]),
            new GraphvizProcessor(new ClassGraphBuilder(new EdgesBuilder())),
            new NeatoProcessor()
        );
    }

    private ClassDiagramGenerator $generator;
}
