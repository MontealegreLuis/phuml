<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Methods\Method;
use PhUml\Code\Structure;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\ProvidesNumericIds;

class GraphvizProcessorTest extends TestCase
{
    use ProvidesNumericIds;

    /** @test */
    function it_has_a_name()
    {
        $processor = new GraphvizProcessor();

        $name = $processor->name();

        $this->assertEquals('Graphviz', $name);
    }

    /** @test */
    function it_turns_a_code_structure_into_dot_language()
    {
        $processor = new GraphvizProcessor();

        $structure = new Structure();
        $parentInterface = new NumericIdInterface('ParentInterface');
        $interface = new NumericIdInterface('ImplementedInterface', [], [], $parentInterface);
        $parentClass = new NumericIdClass('ParentClass');
        $structure->addClass($parentClass);
        $structure->addClass(new NumericIdClass('ReferencedClass'));
        $structure->addInterface($parentInterface);
        $structure->addInterface($interface);
        $structure->addClass(new NumericIdClass('MyClass', [], [], [
                Method::public('__construct', [
                    Variable::declaredWith('$reference', TypeDeclaration::from('ReferencedClass')),
                ])
            ], [$interface], $parentClass)
        );

        $dotLanguage = $processor->process($structure);

        $this->assertRegExp('/^digraph "([0-9a-f]){40}"/', $dotLanguage);
        $this->assertStringEndsWith('{
splines = true;
overlap = false;
mindist = 0.6;
"101" [label=<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">ParentClass</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>> shape=plaintext color="#2e3436"]
"102" [label=<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">ReferencedClass</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>> shape=plaintext color="#2e3436"]
"103" [label=<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">MyClass</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct($reference: ReferencedClass)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>> shape=plaintext color="#2e3436"]
"101" -> "103" [dir=back arrowtail=empty style=solid color="#2e3436"]
"2" -> "103" [dir=back arrowtail=normal style=dashed color="#2e3436"]
"1" [label=<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12"><I>ParentInterface</I></FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>> shape=plaintext color="#2e3436"]
"2" [label=<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12"><I>ImplementedInterface</I></FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>> shape=plaintext color="#2e3436"]
"1" -> "2" [dir=back arrowtail=empty style=solid color="#2e3436"]
}', $dotLanguage);
    }
}
