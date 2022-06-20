<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Graphviz\Edge;
use PhUml\Graphviz\Node;
use PhUml\TestBuilders\A;

final class EnumGraphBuilderTest extends TestCase
{
    /** @test */
    function it_extract_edges_from_the_traits_it_uses()
    {
        $anotherTrait = A::traitNamed('AnotherTrait');
        $thirdTrait = A::traitNamed('ThirdTrait');
        $enum = A::enum('AnEnum')
            ->using($anotherTrait->name(), $thirdTrait->name())
            ->build();
        $codebase = new Codebase();
        $codebase->add($anotherTrait);
        $codebase->add($thirdTrait);


        $nodes = $this->builder->extractFrom($enum, $codebase);

        $this->assertEquals([
            new Node($enum),
            Edge::use($anotherTrait, $enum),
            Edge::use($thirdTrait, $enum),
        ], $nodes);
    }

    /** @test */
    function it_extracts_edges_from_the_interfaces_it_implements()
    {
        $firstInterface = A::interfaceNamed('FirstInterface');
        $secondInterface = A::interfaceNamed('FirstInterface');
        $enum = A::enum('AnEnum')
            ->implementing($firstInterface->name(), $secondInterface->name())
            ->build();
        $codebase = new Codebase();
        $codebase->add($firstInterface);
        $codebase->add($secondInterface);

        $dotElements = $this->builder->extractFrom($enum, $codebase);

        $this->assertEquals([
            new Node($enum),
            Edge::implementation($firstInterface, $enum),
            Edge::implementation($secondInterface, $enum),
        ], $dotElements);
    }

    /** @before */
    function let()
    {
        $this->builder = new EnumGraphBuilder();
    }

    private EnumGraphBuilder $builder;
}
