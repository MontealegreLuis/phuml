<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;

class PhUmlDiagramWithGraphvizAndDotTest extends TestCase
{
    use CompareImagesTrait;

    /** @test */
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
        $diagram = __DIR__ . '/../../tests/.output/graphviz-dot.png';

        passthru(sprintf(
            'php %s %s -graphviz -dot %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src/classes',
            $diagram
        ));

        $expectedDiagram = __DIR__ . '/../images/graphviz-dot.png';
        $this->expectOutputString($success);
        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @test */
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
        $diagram = __DIR__ . '/../../tests/.output/graphviz-dot-recursive.png';

        passthru(sprintf(
            'php %s -r %s -graphviz -dot %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src',
            $diagram
        ));

        $expectedDiagram = __DIR__ . '/../images/graphviz-dot-recursive.png';
        $this->expectOutputString($success);
        $this->assertImagesSame($expectedDiagram, $diagram);
    }
}
