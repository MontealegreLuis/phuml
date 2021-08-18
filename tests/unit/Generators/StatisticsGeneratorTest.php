<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Console\Commands\GeneratorInput;
use PhUml\TestBuilders\A;

final class StatisticsGeneratorTest extends TestCase
{
    /** @test */
    function it_shows_the_statistics_of_a_directory()
    {
        $expectedStatistics = <<<STATS
phUML generated statistics
==========================

General statistics
------------------

Classes:    2
Interfaces: 0

Attributes: 5 (3 are typed)
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
        $generator = StatisticsGenerator::fromConfiguration(A::statisticsGeneratorConfiguration()->build());

        $generator->generate($this->input);

        $this->assertEquals($expectedStatistics, file_get_contents($this->statisticsFile));
    }

    /** @test */
    function it_shows_the_statistics_of_a_directory_using_a_recursive_finder()
    {
        $expectedStatistics = <<<STATS
phUML generated statistics
==========================

General statistics
------------------

Classes:    20
Interfaces: 0

Attributes: 24 (6 are typed)
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
        $configuration = A::statisticsGeneratorConfiguration()->recursive()->build();
        $generator = StatisticsGenerator::fromConfiguration($configuration);

        $generator->generate($this->input);

        $this->assertEquals($expectedStatistics, file_get_contents($this->statisticsFile));
    }

    /** @before */
    function let()
    {
        $this->statisticsFile  = __DIR__ . '/../../resources/.output/statistics.txt';
        $this->input = GeneratorInput::textFile(
            ['directory' => __DIR__ . '/../../resources/.code/classes', 'output' => $this->statisticsFile]
        );
        if (file_exists($this->statisticsFile)) {
            unlink($this->statisticsFile);
        }
    }

    private string $statisticsFile;

    private GeneratorInput $input;
}
