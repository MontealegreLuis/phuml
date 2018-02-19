<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PHPUnit\Framework\TestCase;

class ExternalDefinitionsResolverTest extends TestCase
{
    /** @test */
    function it_does_not_change_the_definitions_if_no_relations_are_declared()
    {
        $definitions = new RawDefinitions();
        $resolver = new ExternalDefinitionsResolver();

        $definitions->addExternalClass('AClass');
        $definitions->addExternalClass('AnotherClass');
        $definitions->addExternalInterface('AnInterface');
        $definitions->addExternalInterface('AnotherInterface');

        $resolver->resolve($definitions);

        $this->assertCount(4, $definitions->all());
    }

    /** @test */
    function it_adds_external_interfaces()
    {
        $definitions = new RawDefinitions();
        $resolver = new ExternalDefinitionsResolver();

        $definitions->add(RawDefinition::class(['class' => 'AClass', 'implements' => [
            'AnExternalInterface', 'AnExistingInterface',
        ]]));
        $definitions->add(RawDefinition::interface(['interface' => 'AnInterface', 'extends' => ['AnotherExternalInterface']]));
        $definitions->add(RawDefinition::interface(['interface' => 'AnExistingInterface']));

        $resolver->resolve($definitions);

        $this->assertCount(5, $definitions->all());
        $this->assertArrayHasKey('AnExternalInterface', $definitions->all());
        $this->assertArrayHasKey('AnotherExternalInterface', $definitions->all());
    }

    /** @test */
    function it_adds_external_classes()
    {
        $definitions = new RawDefinitions();
        $resolver = new ExternalDefinitionsResolver();

        $definitions->add(RawDefinition::class(['class' => 'AClass', 'extends' => 'AnExternalClass', 'implements' => []]));
        $definitions->add(RawDefinition::class(['class' => 'AnotherClass', 'extends' => 'AnotherExternalClass', 'implements' => []]));

        $resolver->resolve($definitions);

        $this->assertCount(4, $definitions->all());
        $this->assertArrayHasKey('AnExternalClass', $definitions->all());
        $this->assertArrayHasKey('AnotherExternalClass', $definitions->all());
    }
}
