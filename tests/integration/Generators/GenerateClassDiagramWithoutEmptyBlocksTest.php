<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\TraitGraphBuilder;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Graphviz\Styles\NonEmptyBlocksStyle;
use PhUml\Parser\Code\ParserBuilder;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

final class GenerateClassDiagramWithoutEmptyBlocksTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_removes_empty_blocks_if_only_definition_names_are_shown()
    {
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../../resources/.code/classes'));
        $diagram = __DIR__ . '/../../resources/.output/graphviz-dot-without-empty-blocks.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-without-empty-blocks.png';

        $this->generator->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @before*/
    function createGenerator()
    {
        $parser = (new ParserBuilder())->excludeMethods()->excludeAttributes()->build();
        $this->generator = new ClassDiagramGenerator(
            new CodeParser($parser),
            new GraphvizProcessor(
                new ClassGraphBuilder(),
                new InterfaceGraphBuilder(),
                new TraitGraphBuilder(),
                new DigraphPrinter(new TemplateEngine(), new NonEmptyBlocksStyle())
            ),
            new DotProcessor()
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    /** @var ClassDiagramGenerator */
    private $generator;
}
