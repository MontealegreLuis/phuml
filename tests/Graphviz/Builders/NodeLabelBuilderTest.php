<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Templates\TemplateEngine;
use PhUml\Templates\TemplateFailure;
use PhUml\TestBuilders\A;
use RuntimeException;

class NodeLabelBuilderTest extends TestCase
{
    /** @before */
    function createLabel()
    {
        $this->labelBuilder = new NodeLabelBuilder(new TemplateEngine());
    }

    /** @test */
    function it_builds_an_html_label_for_a_class()
    {
        $html = $this->labelBuilder->forClass(new ClassDefinition('AClass'));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_attributes()
    {
        $html = $this->labelBuilder->forClass(A::class('AClass')
            ->withAPublicAttribute('name')
            ->withAPrivateAttribute('age')
            ->withAProtectedAttribute('category', 'string')
            ->build());

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">+name</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">#category: string</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_constants()
    {
        $html = $this->labelBuilder->forClass(A::class('AClass')
            ->withAConstant('NUMERIC', 'int')
            ->withAConstant('NO_TYPE')
            ->build());

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10"><I>+NUMERIC: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10"><I>+NO_TYPE</I></FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_constants_attributes_and_methods()
    {
        $html = $this->labelBuilder->forClass(A::class('AClass')
            ->withAPrivateAttribute('age')
            ->withAProtectedAttribute('category', 'string')
            ->withAPublicMethod('getAge')
            ->withAProtectedMethod(
                'setCategory',
                A::parameter('category')->withType('string')->build()
            )
            ->withAConstant('NUMERIC', 'int')
            ->build());

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="12">AClass</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10"><I>+NUMERIC: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">#category: string</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">+getAge()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">#setCategory(category: string)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface()
    {
        $html = $this->labelBuilder->forInterface(new InterfaceDefinition('AnInterface'));

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#729fcf"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="12">AnInterface</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface_with_methods_and_constants()
    {
        $html = $this->labelBuilder->forInterface(A::interface('AnInterface')
            ->withAPublicMethod('doSomething')
            ->withAPublicMethod('changeValue', A::parameter('$value')->withType('int')->build())
            ->withAConstant('NUMERIC', 'int')
            ->withAConstant('NO_TYPE')
            ->build());

        $this->assertEquals(
            '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#729fcf"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="12">AnInterface</FONT></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10"><I>+NUMERIC: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10"><I>+NO_TYPE</I></FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">+doSomething()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" SIZE="10">+changeValue($value: int)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>>',
            $html
        );
    }

    /** @test */
    function it_fails_to_build_a_label_if_twig_fails()
    {
        $templateEngine = new class() extends TemplateEngine {
            public function render($name, array $context = []): string {
                throw new TemplateFailure(new RuntimeException('Twig runtime error'));
            }
            public function __construct() {} // Constructor does not needs to be run
        };
        $labelBuilder = new NodeLabelBuilder($templateEngine);

        $this->expectException(TemplateFailure::class);

        $labelBuilder->forClass(new ClassDefinition('AnyClass'));
    }

    /** @var NodeLabelBuilder */
    private $labelBuilder;
}
