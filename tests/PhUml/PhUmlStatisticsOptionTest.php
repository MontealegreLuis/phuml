<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class PhUmlStatisticsOptionTest extends TestCase
{
    /** @test */
    function it_shows_the_statistics_of_a_directory()
    {
        $statistics = <<<STATS
Phuml generated statistics
==========================

General statistics
------------------

Classes:    2
Interfaces: 0

Attributes: 3 (0 are typed)
    * private:   3
    * protected: 0
    * public:    0

Functions:  11 
    * private:   1
    * protected: 0
    * public:    10

Average statistics
------------------

Attributes per class: 1.5
Functions per class:  5.5

STATS;
        $output = <<<OUTPUT
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
[|] Running... (This may take some time)
[|] Parsing class structure
[|] Running 'Statistics' processor
[|] Writing generated data to disk

OUTPUT;
        $file = __DIR__ . '/../../tests/.output/statistics.txt';

        passthru(sprintf(
            'php %s %s -statistics %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src/classes',
            $file
        ));

        $this->expectOutputString($output);
        $this->assertStringEqualsFile($file, $statistics);
    }

    /** @test */
    function it_accepts_the_recursive_options_for_the_statistics_processor()
    {
        $statistics = <<<STATS
Phuml generated statistics
==========================

General statistics
------------------

Classes:    19
Interfaces: 0

Attributes: 21 (0 are typed)
    * private:   16
    * protected: 1
    * public:    4

Functions:  86 
    * private:   36
    * protected: 0
    * public:    50

Average statistics
------------------

Attributes per class: 1.11
Functions per class:  4.53

STATS;
        $output = <<<OUTPUT
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
[|] Running... (This may take some time)
[|] Parsing class structure
[|] Running 'Statistics' processor
[|] Writing generated data to disk

OUTPUT;
        $file = __DIR__ . '/../../tests/.output/statistics.txt';

        passthru(sprintf(
            'php %s -r %s -statistics %s',
            __DIR__ . '/../../src/app/phuml',
            __DIR__ . '/../../src/classes',
            $file
        ));

        $this->expectOutputString($output);
        $this->assertStringEqualsFile($file, $statistics);
    }
}
