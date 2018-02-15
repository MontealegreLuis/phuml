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
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Graphviz\Styles\NonEmptyBlocksStyle;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Templates\TemplateEngine;

class GenerateClassDiagramWithThemeTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_php_theme()
    {
        $codeFinder = new CodeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-php-theme.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-php-theme.png';
        $generator = $this->createGenerator('php');

        $generator->generate($codeFinder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_classic_theme()
    {
        $codeFinder = new CodeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-classic-theme.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-classic-theme.png';
        $generator = $this->createGenerator('classic');

        $generator->generate($codeFinder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    private function createGenerator(string $theme): ClassDiagramGenerator
    {
        $generator = new ClassDiagramGenerator(
            new CodeParser(),
            new GraphvizProcessor(
                new ClassGraphBuilder(new EdgesBuilder()),
                new InterfaceGraphBuilder(),
                new DigraphPrinter(new TemplateEngine(), new NonEmptyBlocksStyle($theme))
            ),
            new DotProcessor()
        );
        $generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        return $generator;
    }
}
