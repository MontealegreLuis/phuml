<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\InvalidInitialProcessor;
use PhUml\Processors\InvalidProcessorChain;
use PhUml\Processors\NeatoProcessor;
use PhUml\Processors\Processor;
use PhUml\Processors\StatisticsProcessor;
use Symfony\Component\Finder\Finder;

class plPhumlTest extends TestCase 
{
    /**
     * @test
     * @dataProvider invalidInitialProcessors
     */
    function it_fails_to_accept_an_invalid_initial_processor(Processor $processor)
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
    function it_fails_to_accept_incompatible_processors(Processor $statistics, Processor $next)
    {
        $phUml = new plPhuml();
        $phUml->addProcessor($statistics);

        $this->expectException(InvalidProcessorChain::class);
        $phUml->addProcessor($next);
    }

    function incompatibleStatisticsCombinations()
    {
        return [
            'statistics -> dot' => [new StatisticsProcessor(), new DotProcessor()],
            'statistics -> neato' => [new StatisticsProcessor(), new NeatoProcessor()],
            'statistics -> graphviz' => [new StatisticsProcessor(), new GraphvizProcessor()],
            'graphviz -> statistics' => [new GraphvizProcessor(), new StatisticsProcessor()],
        ];
    }

    /** @test */
    function it_finds_files_only_in_the_given_directory()
    {
        $phUml = new plPhuml();

        $phUml->addDirectory(__DIR__ . '/../.code/classes', false);

        $this->assertCount(2, $phUml->files());
        $this->assertRegExp('/class plBase/', $phUml->files()[0]);
        $this->assertRegExp('/class plPhuml/', $phUml->files()[1]);
    }

    /** @test */
    function it_finds_files_recursively()
    {
        $finder = new Finder();
        $finder->sortByName();
        $phUml = new plPhuml(new TokenParser(), new CodeFinder($finder));

        $phUml->addDirectory(__DIR__ . '/../.code/interfaces');

        $this->assertCount(4, $phUml->files());
        $this->assertRegExp('/abstract class plStructureGenerator/', $phUml->files()[0]);
        $this->assertRegExp('/abstract class plProcessor/', $phUml->files()[1]);
        $this->assertRegExp('/abstract class plExternalCommandProcessor/', $phUml->files()[2]);
        $this->assertRegExp('/abstract class plGraphvizProcessorStyle/', $phUml->files()[3]);
    }
}
