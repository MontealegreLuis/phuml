<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\OutputFilePath;
use PhUml\Processors\StatisticsProcessor;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

final class GenerateStatisticsTest extends TestCase
{
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
        $parser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $generator = new StatisticsGenerator($parser, new StatisticsProcessor());
        $display = new ConsoleProgressDisplay(new NullOutput());
        $finder = SourceCodeFinder::nonRecursive();
        $sourceCode = $finder->find($this->pathToCode);

        $generator->generate($sourceCode, $this->statisticsFile, $display);

        $this->assertStringEqualsFile($this->statisticsFile->value(), $statistics);
    }

    /** @test */
    function it_shows_the_statistics_of_a_directory_using_a_recursive_finder()
    {
        $statistics = <<<STATS
phUML generated statistics
==========================

General statistics
------------------

Classes:    20
Interfaces: 0

Attributes: 24 (5 are typed)
    * private:   18
    * protected: 2
    * public:    4

Functions:  87
    * private:   36
    * protected: 0
    * public:    51

Average statistics
------------------

Attributes per class: 1.2
Functions per class:  4.35

STATS;
        $parser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $generator = new StatisticsGenerator($parser, new StatisticsProcessor());
        $display = new ConsoleProgressDisplay(new NullOutput());
        $finder = SourceCodeFinder::recursive();
        $sourceCode = $finder->find($this->pathToCode);

        $generator->generate($sourceCode, $this->statisticsFile, $display);

        $this->assertStringEqualsFile($this->statisticsFile->value(), $statistics);
    }

    /** @before */
    function let()
    {
        $this->statisticsFile = new OutputFilePath(__DIR__ . '/../../resources/.output/statistics.txt');
        $this->pathToCode = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes');
    }

    private OutputFilePath $statisticsFile;

    private CodebaseDirectory $pathToCode;
}
