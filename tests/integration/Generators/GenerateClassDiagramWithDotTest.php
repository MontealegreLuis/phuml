<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Console\Commands\GeneratorInput;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

final class GenerateClassDiagramWithDotTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $display = new ConsoleProgressDisplay(new NullOutput());
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot.png';
        $arguments = [
            'directory' => __DIR__ . '/../../resources/.code/classes',
            'output' => $diagramPath,
        ];
        $input = new GeneratorInput($arguments, $display);
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
        $display = new ConsoleProgressDisplay(new NullOutput());
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot-recursive.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-recursive.png';
        $arguments = [
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $diagramPath,
        ];
        $input = new GeneratorInput($arguments, $display);
        $configuration = A::classDiagramConfiguration()->recursive()->withAssociations()->build();
        $generator = ClassDiagramGenerator::fromConfiguration($configuration);

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }
}
