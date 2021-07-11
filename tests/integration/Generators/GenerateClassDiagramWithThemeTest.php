<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use GrumPHP\Util\Filesystem;
use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Graphviz\Builders\TraitGraphBuilder;
use PhUml\Graphviz\DigraphPrinter;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\ThemeName;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputFilePath;
use PhUml\Templates\TemplateEngine;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

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
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-php-theme.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-php-theme.png';
        $generator = $this->createGenerator('php');
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);

        $generator->generate($codebase, $diagram, $this->display);

        $this->assertImagesSame($expectedDiagram, $diagram->value());
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_classic_theme()
    {
        $codeFinder = SourceCodeFinder::recursive();
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-classic-theme.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-classic-theme.png';
        $generator = $this->createGenerator('classic');
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);

        $generator->generate($codebase, $diagram, $this->display);

        $this->assertImagesSame($expectedDiagram, $diagram->value());
    }

    private function createGenerator(string $theme): ClassDiagramGenerator
    {
        $generator = new ClassDiagramGenerator(
            new GraphvizProcessor(
                new ClassGraphBuilder(new EdgesBuilder()),
                new InterfaceGraphBuilder(),
                new TraitGraphBuilder(),
                new DigraphPrinter(new TemplateEngine(), DigraphStyle::withoutEmptyBlocks(new ThemeName($theme)))
            ),
            ImageProcessor::dot(new Filesystem())
        );
        $this->display = new ConsoleProgressDisplay(new NullOutput());
        return $generator;
    }

    private ConsoleProgressDisplay $display;
}
