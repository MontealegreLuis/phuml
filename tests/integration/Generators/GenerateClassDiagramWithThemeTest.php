<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Console\Commands\GeneratorInput;
use PhUml\TestBuilders\A;

final class GenerateClassDiagramWithThemeTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_php_theme()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot-php-theme.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-php-theme.png';
        $input = GeneratorInput::pngFile([
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $diagramPath,
        ]);
        $configuration = A::classDiagramConfiguration()
            ->recursive()
            ->withAssociations()
            ->withoutEmptyBlocks()
            ->withTheme('php')
            ->build();
        $generator = ClassDiagramGenerator::fromConfiguration($configuration);

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_the_classic_theme()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot-classic-theme.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-classic-theme.png';
        $input = GeneratorInput::pngFile([
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $diagramPath,
        ]);
        $configuration = A::classDiagramConfiguration()
            ->recursive()
            ->withAssociations()
            ->withoutEmptyBlocks()
            ->withTheme('classic')
            ->build();
        $generator = ClassDiagramGenerator::fromConfiguration($configuration);

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }
}
