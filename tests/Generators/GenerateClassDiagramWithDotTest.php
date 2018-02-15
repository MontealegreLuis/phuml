<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;

class GenerateClassDiagramWithDotTest extends TestCase
{
    use CompareImagesTrait;

    /** @before */
    function createGenerator()
    {
        $this->generator = new ClassDiagramGenerator(
            new CodeParser(),
            new GraphvizProcessor(new ClassGraphBuilder(new EdgesBuilder())),
            new DotProcessor()
        );
    }

    /** @test */
    function it_fails_to_generate_diagram_if_a_command_is_not_provided()
    {
        $this->expectException(LogicException::class);
        $this->generator->generate(new NonRecursiveCodeFinder(), 'wont-be-generated.png');
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot.png';

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
        $codeFinder = new CodeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-recursive.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-recursive.png';

        $this->generator->generate($codeFinder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @var ClassDiagramGenerator */
    private $generator;
}
