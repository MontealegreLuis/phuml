<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\StringCodeFinder;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Parser\Code\ExternalDefinitionsResolver;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;

final class GenerateClassDiagramWithNeatoTest extends TestCase
{
    use CompareImagesTrait;

    /** @test */
    function it_fails_to_generate_diagram_if_a_command_is_not_provided()
    {
        $this->expectException(LogicException::class);
        $this->generator->generate(new StringCodeFinder(), 'wont-be-generated.png');
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes');
        $finder = SourceCodeFinder::nonRecursive($directory);
        $diagram = __DIR__ . '/../../resources/.output/graphviz-neato.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato.png';

        $this->generator->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_a_recursive_finder()
    {
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $codeFinder = SourceCodeFinder::recursive(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $diagram = __DIR__ . '/../../resources/.output/graphviz-neato-recursive.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato-recursive.png';

        $this->generator->generate($codeFinder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
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
