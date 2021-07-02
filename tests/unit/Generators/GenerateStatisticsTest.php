<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\StringCodeFinder;
use PhUml\Parser\Code\ExternalDefinitionsResolver;
use PhUml\Parser\Code\PhpCodeParser;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\StatisticsProcessor;

final class GenerateStatisticsTest extends TestCase
{
    /** @test */
    function it_fails_to_generate_the_statistics_if_a_command_is_not_provided()
    {
        $generator = new StatisticsGenerator(new CodeParser(new PhpCodeParser()), new StatisticsProcessor());

        $this->expectException(LogicException::class);
        $generator->generate(new StringCodeFinder(), 'wont-be-generated.txt');
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

        $generator = new StatisticsGenerator(new CodeParser(new PhpCodeParser()), new StatisticsProcessor());
        $generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = SourceCodeFinder::nonRecursive(new CodebaseDirectory($this->pathToCode));

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

        $parser = new CodeParser(new PhpCodeParser(), [new ExternalDefinitionsResolver()]);
        $generator = new StatisticsGenerator($parser, new StatisticsProcessor());
        $generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = SourceCodeFinder::recursive(new CodebaseDirectory($this->pathToCode));

        $generator->generate($finder, $this->statisticsFile);

        $this->assertStringEqualsFile($this->statisticsFile, $statistics);
    }

    /** @before */
    function configure()
    {
        $this->statisticsFile = __DIR__ . '/../../resources/.output/statistics.txt';
        $this->pathToCode = __DIR__ . '/../../resources/.code/classes';
    }

    private ?string $statisticsFile = null;

    private ?string $pathToCode = null;
}
