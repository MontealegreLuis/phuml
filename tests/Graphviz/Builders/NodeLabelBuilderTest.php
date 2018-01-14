<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Method;
use PhUml\Code\Variable;
use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;
use Twig_Error_Runtime as RuntimeError;

class NodeLabelBuilderTest extends TestCase
{
    /** @test */
    function it_builds_an_html_label_for_a_class()
    {
        $html = $this->labelBuilder->forClass(new ClassDefinition('AClass'));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_attributes()
    {
        $html = $this->labelBuilder->forClass(new ClassDefinition('AClass', [
            new Attribute('name'),
            new Attribute('age', 'private'),
            new Attribute('category', 'protected', 'string')
        ]));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+name</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_attributes_and_methods()
    {
        $html = $this->labelBuilder->forClass(new ClassDefinition('AClass', [
            new Attribute('age', 'private'),
            new Attribute('category', 'protected', 'string')
        ], [
            new Method('getAge'),
            new Method('setCategory', 'protected', [new Variable('category', 'string')])
        ]));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getAge()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#setCategory( string category )</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface()
    {
        $html = $this->labelBuilder->forInterface(new InterfaceDefinition('AnInterface'));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#729fcf"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AnInterface</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface_with_methods()
    {
        $html = $this->labelBuilder->forInterface(new InterfaceDefinition('AnInterface', [
            new Method('doSomething'),
            new Method('changeValue', 'public', [
                new Variable('value', 'int')
            ])
        ]));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#729fcf"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AnInterface</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+doSomething()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+changeValue( int value )</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_fails_to_build_a_label_if_twig_fails()
    {
        $templateEngine = new class() extends TemplateEngine {
            public function render($name, array $context = []) {
                throw new RuntimeError("Twig runtime error");
            }
            public function __construct() {} // Constructor does not needs to be run
        };
        $labelBuilder = new NodeLabelBuilder($templateEngine, new HtmlLabelStyle());

        $this->expectException(NodeLabelError::class);

        $labelBuilder->forClass(new ClassDefinition('AnyClass'));
    }

    /** @before */
    function createLabel()
    {
        $this->labelBuilder = new NodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../../../src/Graphviz/templates')
        ), new HtmlLabelStyle());
    }

    /** @var NodeLabelBuilder */
    private $labelBuilder;
}
