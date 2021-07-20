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

final class GenerateClassDiagramWithDotTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot.png';
        $input = GeneratorInput::pngFile([
            'directory' => __DIR__ . '/../../resources/.code/classes',
            'output' => $diagramPath,
        ]);
        $generator = ClassDiagramGenerator::fromConfiguration(A::classDiagramConfiguration()->build());

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_a_recursive_finder()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot-recursive.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-recursive.png';
        $input = GeneratorInput::pngFile([
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $diagramPath,
        ]);
        $configuration = A::classDiagramConfiguration()->recursive()->withAssociations()->build();
        $generator = ClassDiagramGenerator::fromConfiguration($configuration);

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }
}
