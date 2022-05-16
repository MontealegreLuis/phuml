<?php declare(strict_types=1);
/**
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
Interfaces: 1

Attributes: 5 (3 are typed)
    * private:   4
    * protected: 1
    * public:    0

Functions:  15
    * private:   1
    * protected: 0
    * public:    14

Average statistics
------------------

Attributes per class: 2.5
Functions per class:  7.5

STATS;
        $generator = StatisticsGenerator::fromConfiguration(A::statisticsGeneratorConfiguration()->build());

        $generator->generate($this->input);

        $this->assertSame($expectedStatistics, file_get_contents($this->statisticsFile));
    }

    /** @test */
    function it_shows_the_statistics_of_a_directory_using_a_recursive_finder()
    {
        $expectedStatistics = <<<STATS
phUML generated statistics
==========================

General statistics
------------------

Classes:    21
Interfaces: 1

Attributes: 25 (7 are typed)
    * private:   19
    * protected: 2
    * public:    4

Functions:  91
    * private:   36
    * protected: 0
    * public:    55

Average statistics
------------------

Attributes per class: 1.19
Functions per class:  4.33

STATS;
        $configuration = A::statisticsGeneratorConfiguration()->recursive()->build();
        $generator = StatisticsGenerator::fromConfiguration($configuration);

        $generator->generate($this->input);

        $this->assertSame($expectedStatistics, file_get_contents($this->statisticsFile));
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
