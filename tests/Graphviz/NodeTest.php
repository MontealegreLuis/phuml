<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;

class NodeTest extends TestCase
{
    /** @test */
    function it_can_be_a_class_or_an_interface()
    {
        $class = new ClassDefinition('AClass');
        $interface = new InterfaceDefinition('AnInterface');

        $classNode = new Node($class);
        $interfaceNode = new Node($interface);

        $this->assertEquals($class, $classNode->node());
        $this->assertEquals($interface, $interfaceNode->node());
    }

    /** @test */
    function it_knows_its_dot_template()
    {
        $class = new ClassDefinition('AClass');
        $interface = new InterfaceDefinition('AnInterface');

        $classNode = new Node($class);
        $interfaceNode = new Node($interface);

        $this->assertEquals('node', $classNode->dotTemplate());
        $this->assertEquals('node', $interfaceNode->dotTemplate());
    }
}
