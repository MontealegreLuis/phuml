<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

final class NodeTest extends TestCase
{
    /** @test */
    function it_can_be_a_class_or_an_interface()
    {
        $class = A::classNamed('AClass');
        $interface = A::interfaceNamed('AnInterface');

        $classNode = new Node($class);
        $interfaceNode = new Node($interface);

        $this->assertEquals($class, $classNode->definition());
        $this->assertEquals($interface, $interfaceNode->definition());
    }

    /** @test */
    function it_knows_its_dot_template()
    {
        $class = A::classNamed('AClass');
        $interface = A::interfaceNamed('AnInterface');

        $classNode = new Node($class);
        $interfaceNode = new Node($interface);

        $this->assertSame('node', $classNode->dotTemplate());
        $this->assertSame('node', $interfaceNode->dotTemplate());
    }
}
