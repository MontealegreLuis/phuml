<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\ThemeName;
use PhUml\Templates\TemplateEngine;
use PhUml\Templates\TemplateFailure;
use PhUml\TestBuilders\A;
use RuntimeException;

final class DigraphPrinterTest extends TestCase
{
    use WithDotLanguageAssertions;

    /** @test */
    function its_dot_language_representation_contains_an_id_and_basic_display_settings()
    {
        $digraph = new Digraph();

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertMatchesRegularExpression('/^digraph "([0-9a-f]){40}"/', $dotLanguage);
        $this->assertStringContainsString('splines = true;
overlap = false;
mindist = 0.6;', $dotLanguage);
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_attributes()
    {
        $class = A::class('AClass')
            ->withAPublicAttribute('name')
            ->withAPrivateAttribute('age')
            ->withAProtectedAttribute('category', 'string')
            ->build();
        $digraph = new Digraph();
        $digraph->add([new Node($class)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertStringContainsString(
            '<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+name</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category: string</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>',
            $dotLanguage
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_constants()
    {
        $class = A::class('AClass')
            ->withAConstant('NUMERIC', 'int')
            ->withAConstant('NO_TYPE')
            ->build();
        $digraph = new Digraph();
        $digraph->add([new Node($class)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertStringContainsString(
            '<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+NUMERIC: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+NO_TYPE</I></FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>',
            $dotLanguage
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_class_with_constants_attributes_and_methods()
    {
        $class = A::class('AClass')
            ->withAPrivateAttribute('age')
            ->withAProtectedAttribute('category', 'string')
            ->withAPublicMethod('getAge')
            ->withAProtectedMethod(
                'setCategory',
                A::parameter('category')->withType('string')->build()
            )
            ->withAConstant('NUMERIC', 'int')
            ->build();
        $digraph = new Digraph();
        $digraph->add([new Node($class)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertStringContainsString(
            '<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">AClass</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+NUMERIC: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category: string</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getAge()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#setCategory(category: string)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>',
            $dotLanguage
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface()
    {
        $interface = A::interfaceNamed('AnInterface');
        $digraph = new Digraph();
        $digraph->add([new Node($interface)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertStringContainsString(
            '<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12"><I>AnInterface</I></FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>',
            $dotLanguage
        );
    }

    /** @test */
    function it_builds_an_html_label_for_an_interface_with_methods_and_constants()
    {
        $interface = A::interface('AnInterface')
            ->withAPublicMethod('doSomething')
            ->withAPublicMethod('changeValue', A::parameter('$value')->withType('int')->build())
            ->withAConstant('NUMERIC', 'int')
            ->withAConstant('NO_TYPE')
            ->build();
        $digraph = new Digraph();
        $digraph->add([new Node($interface)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertStringContainsString(
            '<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12"><I>AnInterface</I></FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+NUMERIC: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+NO_TYPE</I></FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+doSomething()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+changeValue($value: int)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>',
            $dotLanguage
        );
    }

    /** @test */
    function it_builds_an_html_label_for_a_trait_with_attributes_and_methods()
    {
        $trait = A::trait('ATrait')
            ->withAPrivateAttribute('age')
            ->withAProtectedAttribute('category', 'string')
            ->withAPublicMethod('getAge')
            ->withAProtectedMethod(
                'setCategory',
                A::parameter('category')->withType('string')->build()
            )
            ->withAMethod(A::method('count')->protected()->static()->build())
            ->withAMethod(A::method('display')->private()->abstract()->build())
            ->build();
        $digraph = new Digraph();
        $digraph->add([new Node($trait)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertStringContainsString(
            '<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">&lt;&lt;trait&gt;&gt;</FONT><BR/><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12"><I>ATrait</I></FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-age</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#category: string</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getAge()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#setCategory(category: string)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>#count()</U></FONT><BR ALIGN="LEFT"/><I><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-display()</FONT></I><BR ALIGN="LEFT"/></TD></TR></TABLE>',
            $dotLanguage
        );
    }

    /** @test */
    function it_represents_a_single_definition_as_dot_language()
    {
        $class = A::classNamed('TestClass');
        $digraph = new Digraph();
        $digraph->add([new Node($class)]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertNode($class, $dotLanguage);
    }

    /** @test */
    function it_represents_inheritance_as_dot_language()
    {
        $parentClass = A::classNamed('ParentClass');
        $class = A::class('TestClass')->extending($parentClass->name())->build();
        $digraph = new Digraph();
        $digraph->add([
            new Node($parentClass),
            new Node($class),
            Edge::inheritance($parentClass, $class),
        ]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertNode($parentClass, $dotLanguage);
        $this->assertNode($class, $dotLanguage);
        $this->assertInheritance($class, $parentClass, $dotLanguage);
    }

    /** @test */
    function it_represents_a_class_using_a_trait_as_dot_language()
    {
        $trait = A::traitNamed('ATrait');
        $class = A::class('TestClass')->extending($trait->name())->build();
        $digraph = new Digraph();
        $digraph->add([
            new Node($trait),
            new Node($class),
            Edge::use($trait, $class),
        ]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertNode($trait, $dotLanguage);
        $this->assertNode($class, $dotLanguage);
        $this->assertUseTrait($class, $trait, $dotLanguage);
    }

    /** @test */
    function it_represents_interfaces_implementations_as_dot_language()
    {
        $anInterface = A::interfaceNamed('AnInterface');
        $anotherInterface = A::interfaceNamed('AnotherInterface');
        $class = A::class('TestClass')
            ->implementing($anInterface->name(), $anotherInterface->name())
            ->build();
        $digraph = new Digraph();
        $digraph->add([
            new Node($class),
            Edge::implementation($anInterface, $class),
            Edge::implementation($anotherInterface, $class),
            new Node($anInterface),
            new Node($anotherInterface),
        ]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertNode($class, $dotLanguage);
        $this->assertNode($anInterface, $dotLanguage);
        $this->assertNode($anotherInterface, $dotLanguage);
        $this->assertImplementation($class, $anInterface, $dotLanguage);
        $this->assertImplementation($class, $anotherInterface, $dotLanguage);
    }

    /** @test */
    function it_represents_constructor_dependencies_as_associations_in_dot_language()
    {
        $reference = A::classNamed('AReference');
        $class = A::class('TestClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$aReference')->withType('AReference')->build()
            )
            ->build();
        $digraph = new Digraph();
        $digraph->add([
            new Node($reference),
            Edge::association($reference, $class),
            new Node($class),
        ]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertNode($reference, $dotLanguage);
        $this->assertNode($class, $dotLanguage);
        $this->assertAssociation($reference, $class, $dotLanguage);
    }

    /** @test */
    function it_represents_class_attributes_as_associations_in_dot_language()
    {
        $reference = A::classNamed('AReference');
        $class = A::class('TestClass')
            ->withAPrivateAttribute('$aReference', 'AReference')
            ->build()
        ;
        $digraph = new Digraph();
        $digraph->add([
            new Node($reference),
            Edge::association($reference, $class),
            new Node($class),
        ]);

        $dotLanguage = $this->printer->toDot($digraph);

        $this->assertNode($reference, $dotLanguage);
        $this->assertNode($class, $dotLanguage);
        $this->assertAssociation($reference, $class, $dotLanguage);
    }

    /** @test */
    function it_fails_to_build_a_label_if_twig_fails()
    {
        $templateEngine = new class() extends TemplateEngine {
            public function render(string $template, array $context = []): string
            {
                throw new TemplateFailure(new RuntimeException('Twig runtime error'));
            }
        };
        $printer = new DigraphPrinter($templateEngine, DigraphStyle::default(new ThemeName('phuml')));

        $this->expectException(TemplateFailure::class);
        $this->expectExceptionMessage('Template rendering failed: Twig runtime error');
        $printer->toDot(new Digraph());
    }

    /** @before */
    function let()
    {
        $this->printer = new DigraphPrinter(new TemplateEngine(), DigraphStyle::default(new ThemeName('phuml')));
    }

    private DigraphPrinter $printer;
}
