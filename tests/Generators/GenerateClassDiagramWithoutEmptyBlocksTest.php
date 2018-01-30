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
use PhUml\Graphviz\Builders\NodeLabelBuilder;
use PhUml\Graphviz\Builders\NonEmptyBlocksLabelStyle;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\Builders\NoAttributesBuilder;
use PhUml\Parser\Raw\Builders\NoMethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\Php5Parser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

class GenerateClassDiagramWithoutEmptyBlocksTest extends TestCase
{
    use CompareImagesTrait;

    /** @before*/
    function createGenerator()
    {
        $methodsBuilder = new NoMethodsBuilder();
        $nodeLabelBuilder = new NodeLabelBuilder(new TemplateEngine(), new NonEmptyBlocksLabelStyle());
        $this->generator = new ClassDiagramGenerator(
            new CodeParser(
                new StructureBuilder(),
                new Php5Parser(
                    new RawClassBuilder(new NoAttributesBuilder(), $methodsBuilder),
                    new RawInterfaceBuilder($methodsBuilder)
                )
            ),
            new GraphvizProcessor(
                new ClassGraphBuilder($nodeLabelBuilder),
                new InterfaceGraphBuilder($nodeLabelBuilder)
            ),
            new DotProcessor()
        );
    }

    /**
     * @test
     * @group snapshot
     */
    function it_removes_empty_blocks_if_only_definition_names_are_shown()
    {
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-without-empty-blocks.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-without-empty-blocks.png';

        $this->generator->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @var ClassDiagramGenerator */
    private $generator;
}
