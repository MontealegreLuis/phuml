<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Actions;

use LogicException;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\StatisticsProcessor;

class GenerateStatisticsTest extends TestCase
{
    /** @test */
    function it_fails_to_generate_the_statistics_if_command_is_not_provided()
    {
        $action = new GenerateStatistics(new TokenParser(), new StatisticsProcessor());

        $this->expectException(LogicException::class);
        $action->generate(new CodeFinder(), 'wont-be-generated.txt');
    }

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
        $file = __DIR__ . '/../../tests/.output/statistics.txt';

        $action = new GenerateStatistics(new TokenParser(), new StatisticsProcessor());
        $action->attach($this->prophesize(CanGenerateStatistics::class)->reveal());
        $finder = new CodeFinder();
        $finder->addDirectory(__DIR__ . '/../.code/classes', false);

        $action->generate($finder, $file);

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
        $file = __DIR__ . '/../../tests/.output/statistics.txt';

        $action = new GenerateStatistics(new TokenParser(), new StatisticsProcessor());
        $action->attach($this->prophesize(CanGenerateStatistics::class)->reveal());
        $finder = new CodeFinder();
        $finder->addDirectory(__DIR__ . '/../.code/classes');

        $action->generate($finder, $file);

        $this->assertStringEqualsFile($file, $statistics);
    }
}
