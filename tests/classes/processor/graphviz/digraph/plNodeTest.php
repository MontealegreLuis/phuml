<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plNodeTest extends TestCase
{
    /** @test */
    function it_can_be_represented_using_dot_language()
    {
        $classOrInterface = $this->prophesize(plHasNodeIdentifier::class);
        $classOrInterface->identifier()->willReturn('ClassOrInterface');

        $node = new plNode(
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
