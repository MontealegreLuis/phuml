<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Methods\Method;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
use PhUml\Fakes\ClassNameLabelBuilder;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\ProvidesNumericIds;

class DigraphTest extends TestCase
{
    use ProvidesNumericIds;

    /** @test */
    function its_dot_language_representation_contains_an_id_and_basic_display_settings()
    {
        $digraph = new Digraph();

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertRegExp('/^digraph "([0-9a-f]){40}"/', $dotLanguage);
        $this->assertContains('splines = true;
overlap = false;
mindist = 0.6;', $dotLanguage);
    }

    /** @test */
    function it_represents_a_single_definition_as_dot_language()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $class = new NumericIdClass('TestClass');
        $digraph = new Digraph();
        $digraph->add([new Node($class, $labelBuilder->forClass($class))]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertContains('"101" [label=<<table><tr><td>TestClass</td></tr></table>> shape=plaintext]', $dotLanguage);
    }

    /** @test */
    function it_represents_inheritance_as_dot_language()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $parentClass = new NumericIdClass('ParentClass');
        $class = new NumericIdClass('TestClass', [], [], [], [], $parentClass);
        $digraph = new Digraph();
        $digraph->add([
            new Node($parentClass, $labelBuilder->forClass($parentClass)),
            new Node($class, $labelBuilder->forClass($class)),
            Edge::inheritance($parentClass, $class),
        ]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertContains('"101" [label=<<table><tr><td>ParentClass</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"102" [label=<<table><tr><td>TestClass</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"101" -> "102" [dir=back arrowtail=empty style=solid]', $dotLanguage);
    }

    /** @test */
    function it_represents_interfaces_implementations_as_dot_language()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $anInterface = new NumericIdInterface('AnInterface');
        $anotherInterface = new NumericIdInterface('AnotherInterface');
        $class = new NumericIdClass('TestClass', [], [], [], [$anInterface, $anotherInterface]);
        $digraph = new Digraph();
        $digraph->add([
            new Node($class, $labelBuilder->forClass($class)),
            Edge::implementation($anInterface, $class),
            Edge::implementation($anotherInterface, $class),
            new Node($anInterface, $labelBuilder->forInterface($anInterface)),
            new Node($anotherInterface, $labelBuilder->forInterface($anotherInterface)),
        ]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertContains('"101" [label=<<table><tr><td>TestClass</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"1" [label=<<table><tr><td>AnInterface</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"2" [label=<<table><tr><td>AnotherInterface</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"1" -> "101" [dir=back arrowtail=normal style=dashed]', $dotLanguage);
        $this->assertContains('"2" -> "101" [dir=back arrowtail=normal style=dashed]', $dotLanguage);
    }

    /** @test */
    function it_represents_constructor_dependencies_as_associations_in_dot_language()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $referenceClass = new NumericIdClass('AReference');
        $testClass = new NumericIdClass('TestClass', [], [], [
            Method::public('__construct', [
                Variable::declaredWith('aReference', TypeDeclaration::from('AReference'))
            ])
        ]);
        $digraph = new Digraph();
        $digraph->add([
            new Node($referenceClass, $labelBuilder->forClass($referenceClass)),
            Edge::association($referenceClass, $testClass),
            new Node($testClass, $labelBuilder->forClass($testClass)),
        ]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertContains('"101" [label=<<table><tr><td>AReference</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"102" [label=<<table><tr><td>TestClass</td></tr></table>> shape=plaintext]', $dotLanguage);
        $this->assertContains('"101" -> "102" [dir=back arrowtail=none style=dashed]', $dotLanguage);
    }

    /** @test */
    function it_represents_class_attributes_as_associations_in_dot_language()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $referenceClass = new NumericIdClass('AReference');
        $testClass = new NumericIdClass('TestClass', [], [
            Attribute::private('$aReference', TypeDeclaration::from('AReference'))
        ], []);
        $digraph = new Digraph();
        $digraph->add([
            new Node($referenceClass, $labelBuilder->forClass($referenceClass)),
            Edge::association($referenceClass, $testClass),
            new Node($testClass, $labelBuilder->forClass($testClass)),
        ]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertContains('"101" [label=<<table><tr><td>AReference</td></tr></table>> shape=plaintext]', $dotLanguage);
    }
}
