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
        $input = GeneratorInput::pngFile([
            'directory' => __DIR__ . '/../../resources/.code/classes',
            'output' => $diagramPath,
        ]);

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
    }

    private ClassDiagramGenerator $generator;
}
