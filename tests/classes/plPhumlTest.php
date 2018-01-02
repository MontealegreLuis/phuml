<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;
use PhUml\Processors\InvalidInitialProcessor;
use PhUml\Processors\InvalidProcessorChain;

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
            'neato' => [new plNeatoProcessor()],
            'dot' => [new plDotProcessor()],
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
            'statistics -> dot' => [new plStatisticsProcessor(), new plDotProcessor()],
            'statistics -> neato' => [new plStatisticsProcessor(), new plNeatoProcessor()],
            'statistics -> graphviz' => [new plStatisticsProcessor(), new plGraphvizProcessor()],
            'graphviz -> statistics' => [new plGraphvizProcessor(), new plStatisticsProcessor()],
        ];
    }
}
