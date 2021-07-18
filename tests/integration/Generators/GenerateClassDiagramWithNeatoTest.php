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
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

final class GenerateClassDiagramWithNeatoTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-neato.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato.png';
        $arguments = [
            'directory' => __DIR__ . '/../../resources/.code/classes',
            'output' => $diagramPath,
        ];
        $input = new GeneratorInput($arguments, $this->display);
        $generator = ClassDiagramGenerator::fromConfiguration(A::classDiagramConfiguration()->usingNeato()->build());

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_a_recursive_finder()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-neato-recursive.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-neato-recursive.png';
        $arguments = [
            'directory' => __DIR__ . '/../../resources/.code',
            'output' => $diagramPath,
        ];
        $input = new GeneratorInput($arguments, $this->display);
        $configuration = A::classDiagramConfiguration()->recursive()->withAssociations()->usingNeato()->build();
        $generator = ClassDiagramGenerator::fromConfiguration($configuration);

        $generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }

    /** @before */
    function let()
    {
        $this->display = new ConsoleProgressDisplay(new NullOutput());
    }

    private ConsoleProgressDisplay $display;
}
