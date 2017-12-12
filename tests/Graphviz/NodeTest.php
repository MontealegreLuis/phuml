<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    /** @test */
    function it_can_be_represented_using_dot_language()
    {
        $classOrInterface = $this->prophesize(HasNodeIdentifier::class);
        $classOrInterface->identifier()->willReturn('ClassOrInterface');

        $node = new Node(
            $classOrInterface->reveal(),
            '<<table><tr><td>ClassOrInterface</td></tr><tr><td> </td></tr></table>>'
        );

        $nodeInDotLanguage = $node->toDotLanguage();

        $this->assertEquals(
            "\"ClassOrInterface\" [label=<<table><tr><td>ClassOrInterface</td></tr><tr><td> </td></tr></table>> shape=plaintext]\n",
            $nodeInDotLanguage
        );
    }
}
