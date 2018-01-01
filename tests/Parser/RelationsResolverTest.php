<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;

class RelationsResolverTest extends TestCase
{
    /** @test */
    function it_does_not_change_the_definitions_if_no_relations_are_declared()
    {
        $definitions = new Definitions();
        $resolver = new RelationsResolver();

        $definitions->addExternalClass('AClass');
        $definitions->addExternalClass('AnotherClass');
        $definitions->addExternalInterface('AnInterface');
        $definitions->addExternalInterface('AnotherInterface');

        $resolver->resolve($definitions);

        $this->assertCount(4, $definitions->all());
    }

    /** @test */
    function it_adds_missing_interfaces()
    {
        $definitions = new Definitions();
        $resolver = new RelationsResolver();

        $definitions->add(['class' => 'AClass', 'implements' => [
            'AnExternalInterface', 'AnExistingInterface',
        ]]);
        $definitions->add(['interface' => 'AnInterface', 'extends' => 'AnotherExternalInterface']);
        $definitions->add(['interface' => 'AnExistingInterface']);

        $resolver->resolve($definitions);

        $this->assertCount(5, $definitions->all());
        $this->assertArrayHasKey('AnExternalInterface', $definitions->all());
        $this->assertArrayHasKey('AnotherExternalInterface', $definitions->all());
    }

    /** @test */
    function it_adds_missing_classes()
    {
        $definitions = new Definitions();
        $resolver = new RelationsResolver();

        $definitions->add(['class' => 'AClass', 'extends' => 'AnExternalClass', 'implements' => []]);
        $definitions->add(['class' => 'AnotherClass', 'extends' => 'AnotherExternalClass', 'implements' => []]);

        $resolver->resolve($definitions);

        $this->assertCount(4, $definitions->all());
        $this->assertArrayHasKey('AnExternalClass', $definitions->all());
        $this->assertArrayHasKey('AnotherExternalClass', $definitions->all());
    }
}
