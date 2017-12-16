<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;

class PhUmlDiagramWithGraphvizAndDotLinuxTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_graphviz_and_dot_processors()
    {
        $success = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
[|] Running... (This may take some time)
[|] Parsing class structure
[|] Running 'Graphviz' processor
[|] Running 'Dot' processor
[|] Writing generated data to disk

MESSAGE;
        $diagramPath = __DIR__ . '/../../tests/.output/graphviz-dot-linux.png';

        passthru(sprintf(
            'php %s %s -graphviz -dot %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../.code/classes',
            $diagramPath
        ));

        $this->expectOutputString($success);

        $expectedImage = new Imagick(__DIR__ . '/../images/graphviz-dot-linux.png');
        $diagram = new Imagick($diagramPath);
        $this->assertEquals($expectedImage->getImageLength(), $diagram->getImageLength());
        $this->assertImagesSame($expectedImage, $diagram);
    }

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_graphviz_and_dot_processors_using_the_recursive_option()
    {
        $success = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
[|] Running... (This may take some time)
[|] Parsing class structure
[|] Running 'Graphviz' processor
[|] Running 'Dot' processor
[|] Writing generated data to disk

MESSAGE;
        $diagram = __DIR__ . '/../../tests/.output/graphviz-dot-recursive-linux.png';

        passthru(sprintf(
            'php %s -r %s -graphviz -dot %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../.code',
            $diagram
        ));

        $expectedDiagram = __DIR__ . '/../images/graphviz-dot-recursive-linux.png';
        $this->expectOutputString($success);
        $this->assertImagesSame($expectedDiagram, $diagram);
    }
}
