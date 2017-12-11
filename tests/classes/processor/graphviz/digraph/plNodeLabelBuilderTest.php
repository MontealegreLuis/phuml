<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;
use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

class plNodeLabelBuilderTest extends TestCase
{
    /** @test */
    function it_builds_an_html_label_for_a_class()
    {
        $html = $this->labelBuilder->labelForClass(new plPhpClass('AClass'));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_attributes()
    {
        $html = $this->labelBuilder->labelForClass(new plPhpClass('AClass', [
            new plPhpAttribute('name'),
            new plPhpAttribute('age', 'private'),
            new plPhpAttribute('category', 'protected', 'string')
        ]));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+name</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_attributes_and_methods()
    {
        $html = $this->labelBuilder->labelForClass(new plPhpClass('AClass', [
            new plPhpAttribute('age', 'private'),
            new plPhpAttribute('category', 'protected', 'string')
        ], [
            new plPhpFunction('getAge'),
            new plPhpFunction('setCategory', 'protected', [new plPhpFunctionParameter('category', 'string')])
        ]));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getAge()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#setCategory( string category )</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface()
    {
        $html = $this->labelBuilder->labelForInterface(new plPhpInterface('AnInterface'));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#729fcf"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AnInterface</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface_with_methods()
    {
        $html = $this->labelBuilder->labelForInterface(new plPhpInterface('AnInterface', [
            new plPhpFunction('doSomething'),
            new plPhpFunction('changeValue', 'public', [
                new plPhpFunctionParameter('value', 'int')
            ])
        ]));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#729fcf"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AnInterface</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+doSomething()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+changeValue( int value )</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>>',
            $html
        );
    }

    /** @before */
    function createLabel()
    {
        $this->labelBuilder = new plNodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../../../../../src/classes/processor/graphviz/digraph/templates')
        ), new plGraphvizProcessorDefaultStyle());
    }

    /** @var plNodeLabelBuilder */
    private $labelBuilder;
}
