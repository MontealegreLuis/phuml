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

final class GenerateClassDiagramWithoutEmptyBlocksTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group snapshot
     */
    function it_removes_empty_blocks_if_only_definition_names_are_shown()
    {
        $diagramPath = __DIR__ . '/../../resources/.output/graphviz-dot-without-empty-blocks.png';
        $expectedDiagram = __DIR__ . '/../../resources/images/graphviz-dot-without-empty-blocks.png';
        $arguments = [
            'directory' => __DIR__ . '/../../resources/.code/classes',
            'output' => $diagramPath,
        ];
        $input = new GeneratorInput($arguments, $this->display);

        $this->generator->generate($input);

        $this->assertImagesSame($expectedDiagram, $diagramPath);
    }

    /** @before */
    function let()
    {
        $configuration = A::classDiagramConfiguration()
            ->withoutAttributes()
            ->withoutMethods()
            ->withoutEmptyBlocks()
            ->build();
        $this->generator = ClassDiagramGenerator::fromConfiguration($configuration);
        $this->display = new ConsoleProgressDisplay(new NullOutput());
    }

    private ClassDiagramGenerator $generator;

    private ConsoleProgressDisplay $display;
}
