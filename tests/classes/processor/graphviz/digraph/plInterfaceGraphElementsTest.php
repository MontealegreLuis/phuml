<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class plInterfaceGraphElementsTest extends TestCase
{
    /** @test */
    function it_extracts_the_elements_from_a_single_interface()
    {
        $interface = new plPhpInterface('AnInterface');
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$interface->name}</td></tr></table>>";
        $graphElements = new plInterfaceGraphElements($nodeBuilder);

        $dotElements = $graphElements->extractFrom($interface);

        $this->assertEquals([new plNode($interface, $label)], $dotElements);
    }

    /** @test */
    function it_extracts_the_elements_from_an_interface_with_a_parent()
    {
        $parent = new plPhpInterface('ParentInterface');
        $interface = new plPhpInterface('AnInterface', [], $parent);
        $nodeBuilder = new plClassNameLabelBuilder();
        $label = "<<table><tr><td>{$interface->name}</td></tr></table>>";
        $graphElements = new plInterfaceGraphElements($nodeBuilder);

        $dotElements = $graphElements->extractFrom($interface);

        $this->assertEquals([
            new plNode($interface, $label),
            plEdge::inheritance($parent, $interface)
        ], $dotElements);
    }

}
