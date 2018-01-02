<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class PhUmlIncompatibleProcessorsTest extends TestCase
{
    /**
     * @test
     * @dataProvider incompatibleDotAndNeatoCombinations
     */
    function it_does_not_allow_dot_and_neato_as_initial_processors($firstProcessor, $secondProcessor)
    {
        $incompatibleProcessors = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
A fatal error occured during the process:
Two processors in the chain are incompatible. The first processor's output is "application/phuml-structure". The next Processor in the queue does only support the following input type: text/dot.

MESSAGE;

        passthru(sprintf(
            'php %s %s -%s -%s willnotbecreated.png',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src',
            $firstProcessor,
            $secondProcessor
        ));

        $this->expectOutputString($incompatibleProcessors);
    }

    function incompatibleDotAndNeatoCombinations()
    {
        return [
            'dot -> neato' => ['dot', 'neato'],
            'dot -> statistics' => ['dot', 'statistics'],
            'dot -> graphviz' => ['dot', 'graphviz'],
            'neato -> dot' => ['neato', 'dot'],
            'neato -> statistics' => ['neato', 'statistics'],
            'neato -> graphviz' => ['neato', 'graphviz'],
        ];
    }

    /**
     * @test
     * @dataProvider incompatibleStatisticsCombinations
     */
    function it_does_not_allow_statistics_as_initial_processor($firstProcessor, $secondProcessor)
    {
        $incompatibleProcessors = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
A fatal error occured during the process:
Two processors in the chain are incompatible. The first processor's output is "text/plain". The next Processor in the queue does only support the following input type: text/dot.

MESSAGE;

        passthru(sprintf(
            'php %s %s -%s -%s willnotbecreated.png',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src',
            $firstProcessor,
            $secondProcessor
        ));

        $this->expectOutputString($incompatibleProcessors);
    }

    function incompatibleStatisticsCombinations()
    {
        return [
            'statistics -> neato' => ['statistics', 'dot'],
            'statistics -> dot' => ['statistics', 'neato'],
        ];
    }

    /** @test */
    function it_does_not_allow_statistics_and_graphviz_combination()
    {
        $incompatibleProcessors = <<<MESSAGE
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
A fatal error occured during the process:
Two processors in the chain are incompatible. The first processor's output is "text/plain". The next Processor in the queue does only support the following input type: application/phuml-structure.

MESSAGE;

        passthru(sprintf(
            'php %s %s -statistics -graphviz willnotbecreated.png',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src'
        ));

        $this->expectOutputString($incompatibleProcessors);
    }
}
