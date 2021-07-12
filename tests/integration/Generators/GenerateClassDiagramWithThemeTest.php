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
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\OutputFilePath;
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
        $diagram = new OutputFilePath(__DIR__ . '/../../resources/.output/graphviz-dot-php-theme.png');
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-php-theme.png';
        $generator = $this->createGenerator('php');
        $sourceCode = $codeFinder->find(new CodebaseDirectory(__DIR__ . '/../../resources/.code'));
        $codeParser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $codebase = $codeParser->parse($sourceCode);

        $generator->generate($codebase, $this->display);

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

        $generator->generate($codebase, $this->display);

        $this->assertImagesSame($expectedDiagram, $diagram->value());
    }

    private function createGenerator(string $theme): ClassDiagramGenerator
    {
        $generator = new ClassDiagramGenerator(
            A::graphvizProcessor()->withoutEmptyBlocks()->withTheme($theme)->build(),
            ImageProcessor::dot(new SmartFileSystem())
        );
        $this->display = new ConsoleProgressDisplay(new NullOutput());
        return $generator;
    }

    private ConsoleProgressDisplay $display;
}
