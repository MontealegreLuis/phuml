<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;

class PhUmlDiagramWithGraphvizAndNeatoLinuxTest extends TestCase
{
    use CompareImagesTrait;

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_graphviz_and_neato_processors()
    {
        $success = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
[|] Running... (This may take some time)
[|] Parsing class structure
[|] Running 'Graphviz' processor
[|] Running 'Neato' processor
[|] Writing generated data to disk

MESSAGE;
        $diagram = __DIR__ . '/../../tests/.output/graphviz-neato-linux.png';

        passthru(sprintf(
            'php %s %s -graphviz -neato %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../.code/classes',
            $diagram
        ));

        $expectedDiagram = __DIR__ . '/../images/graphviz-neato-linux.png';
        $this->expectOutputString($success);
        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_graphviz_and_neato_processors_using_the_recursive_option()
    {
        $success = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
[|] Running... (This may take some time)
[|] Parsing class structure
[|] Running 'Graphviz' processor
[|] Running 'Neato' processor
[|] Writing generated data to disk

MESSAGE;
        $diagram = __DIR__ . '/../../tests/.output/graphviz-neato-recursive-linux.png';

        passthru(sprintf(
            'php %s -r %s -graphviz -neato %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../.code',
            $diagram
        ));

        $expectedDiagram = __DIR__ . '/../images/graphviz-neato-recursive-linux.png';
        $this->expectOutputString($success);
        $this->assertImagesSame($expectedDiagram, $diagram);
    }
}
