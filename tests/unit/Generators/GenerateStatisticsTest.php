<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\StatisticsProcessor;

final class GenerateStatisticsTest extends TestCase
{
    /** @test */
    function it_fails_to_generate_the_statistics_if_a_command_is_not_provided()
    {
        $generator = new StatisticsGenerator(new CodeParser(), new StatisticsProcessor());

        $this->expectException(LogicException::class);
        $generator->generate(new NonRecursiveCodeFinder(), 'wont-be-generated.txt');
    }

    /** @test */
    function it_shows_the_statistics_of_a_directory()
    {
        $statistics = <<<STATS
phUML generated statistics
==========================

General statistics
------------------

Classes:    2
Interfaces: 0

Attributes: 5 (2 are typed)
    * private:   4
    * protected: 1
    * public:    0

Functions:  11
    * private:   1
    * protected: 0
    * public:    10

Average statistics
------------------

Attributes per class: 2.5
Functions per class:  5.5

STATS;

        $generator = new StatisticsGenerator(new CodeParser(), new StatisticsProcessor());
        $generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from($this->pathToCode));

        $generator->generate($finder, $this->statisticsFile);

        $this->assertStringEqualsFile($this->statisticsFile, $statistics);
    }

    /** @test */
    function it_shows_the_statistics_of_a_directory_using_a_recursive_finder()
    {
        $statistics = <<<STATS
phUML generated statistics
==========================

General statistics
------------------

Classes:    19
Interfaces: 0

Attributes: 23 (4 are typed)
    * private:   17
    * protected: 2
    * public:    4

Functions:  86
    * private:   36
    * protected: 0
    * public:    50

Average statistics
------------------

Attributes per class: 1.21
Functions per class:  4.53

STATS;

        $generator = new StatisticsGenerator(new CodeParser(), new StatisticsProcessor());
        $generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = new CodeFinder();
        $finder->addDirectory(CodebaseDirectory::from($this->pathToCode));

        $generator->generate($finder, $this->statisticsFile);

        $this->assertStringEqualsFile($this->statisticsFile, $statistics);
    }

    /** @before */
    function configure()
    {
        $this->statisticsFile = __DIR__ . '/../../resources/.output/statistics.txt';
        $this->pathToCode = __DIR__ . '/../../resources/.code/classes';
    }

    /** @var string */
    private $statisticsFile;

    /** @var string */
    private $pathToCode;
}
