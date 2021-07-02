<?php declare(strict_types=1);
/**
 * PHP version 7.2
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
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\ThemeName;
use PhUml\Parser\Code\ParserBuilder;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
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
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes');
        $finder = SourceCodeFinder::nonRecursive($directory);
        $diagram = __DIR__ . '/../../resources/.output/graphviz-dot-without-empty-blocks.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-without-empty-blocks.png';

        $this->generator->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @before*/
    function let()
    {
        $parser = (new ParserBuilder())->excludeMethods()->excludeAttributes()->build();
        $this->generator = new ClassDiagramGenerator(
            new CodeParser($parser),
            new GraphvizProcessor(
                new ClassGraphBuilder(),
                new InterfaceGraphBuilder(),
                new TraitGraphBuilder(),
                new DigraphPrinter(new TemplateEngine(), DigraphStyle::withoutEmptyBlocks(new ThemeName('phuml')))
            ),
            new DotProcessor()
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    private ?ClassDiagramGenerator $generator = null;
}
