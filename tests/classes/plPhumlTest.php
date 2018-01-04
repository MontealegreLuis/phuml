<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\InvalidInitialProcessor;
use PhUml\Processors\InvalidProcessorChain;
use PhUml\Processors\NeatoProcessor;

class plPhumlTest extends TestCase 
{
    /**
     * @test
     * @dataProvider invalidInitialProcessors
     */
    function it_fails_to_accept_an_invalid_initial_processor(plProcessor $processor)
    {
        $phUml = new plPhuml();

        $this->expectException(InvalidInitialProcessor::class);
        $phUml->addProcessor($processor);
    }

    function invalidInitialProcessors()
    {
        return [
            'neato' => [new NeatoProcessor()],
            'dot' => [new DotProcessor()],
        ];
    }

    /**
     * @test
     * @dataProvider incompatibleStatisticsCombinations
     */
    function it_fails_to_accept_incompatible_processors(plProcessor $statistics, plProcessor $next)
    {
        $phUml = new plPhuml();
        $phUml->addProcessor($statistics);

        $this->expectException(InvalidProcessorChain::class);
        $phUml->addProcessor($next);
    }

    function incompatibleStatisticsCombinations()
    {
        return [
            'statistics -> dot' => [new plStatisticsProcessor(), new DotProcessor()],
            'statistics -> neato' => [new plStatisticsProcessor(), new NeatoProcessor()],
            'statistics -> graphviz' => [new plStatisticsProcessor(), new plGraphvizProcessor()],
            'graphviz -> statistics' => [new plGraphvizProcessor(), new plStatisticsProcessor()],
        ];
    }

    /** @test */
    function it_finds_files_only_in_the_given_directory()
    {
        $phUml = new plPhuml();

        $phUml->addDirectory(__DIR__ . '/../.code/classes', false);

        $this->assertCount(2, $phUml->files());
        $this->assertStringEndsWith('base.php', $phUml->files()[0]);
        $this->assertStringEndsWith('phuml.php', $phUml->files()[1]);
    }

    /** @test */
    function it_finds_files_recursively()
    {
        $phUml = new plPhuml();

        $phUml->addDirectory(__DIR__ . '/../.code/interfaces');

        $this->assertCount(4, $phUml->files());
        $this->assertStringEndsWith('processor.php', $phUml->files()[0]);
        $this->assertStringEndsWith('generator.php', $phUml->files()[1]);
        $this->assertStringEndsWith('style.php', $phUml->files()[2]);
        $this->assertStringEndsWith('externalCommand.php', $phUml->files()[3]);
    }
}
