<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Method;
use PhUml\Code\TypeDeclaration;
use PhUml\Code\Variable;
use PhUml\Fakes\ClassNameLabelBuilder;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\ProvidesNumericIds;

class DigraphTest extends TestCase
{
    use ProvidesNumericIds;

    /** @test */
    function it_can_be_represented_as_dot_language_from_a_structure_with_one_definition()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $digraph = new Digraph();

        $class = new NumericIdClass('TestClass');
        $digraph->add([new Node($class, $labelBuilder->forClass($class))]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertRegExp('/^digraph "([0-9a-f]){40}"/', $dotLanguage);
        $this->assertStringEndsWith(' {
splines = true;
overlap = false;
mindist = 0.6;
"101" [label=<<table><tr><td>TestClass</td></tr></table>> shape=plaintext]
}', $dotLanguage);
    }

    /** @test */
    function it_can_be_represented_as_dot_language_from_a_structure_with_several_definitions()
    {
        $labelBuilder = new ClassNameLabelBuilder();
        $digraph = new Digraph();

        $parentInterface = new NumericIdInterface('ParentInterface');
        $childInterface = new NumericIdInterface('ChildInterface', [], [], $parentInterface);
        $anotherInterface = new NumericIdInterface('AnotherInterface');
        $parentClass = new NumericIdClass('ParentClass');
        $referenceClass = new NumericIdClass('AReference');
        $testClass = new NumericIdClass('TestClass', [], [], [
            Method::public('__construct', [
                Variable::declaredWith('aReference', TypeDeclaration::from('AReference'))
            ])
        ], [$childInterface, $anotherInterface], $parentClass);

        $digraph->add([
            new Node($referenceClass, $labelBuilder->forClass($referenceClass)),
            new Node($parentClass, $labelBuilder->forClass($parentClass)),
            Edge::association($referenceClass, $testClass),
            new Node($testClass, $labelBuilder->forClass($testClass)),
            Edge::inheritance($parentClass, $testClass),
            Edge::implementation($childInterface, $testClass),
            Edge::implementation($anotherInterface, $testClass),
            new Node($parentInterface, $labelBuilder->forInterface($parentInterface)),
            new Node($childInterface, $labelBuilder->forInterface($childInterface)),
            Edge::inheritance($parentInterface, $childInterface),
            new Node($anotherInterface, $labelBuilder->forInterface($anotherInterface)),
        ]);

        $dotLanguage = $digraph->toDotLanguage();

        $this->assertRegExp('/^digraph "([0-9a-f]){40}"/', $dotLanguage);
        $this->assertStringEndsWith(' {
splines = true;
overlap = false;
mindist = 0.6;
"102" [label=<<table><tr><td>AReference</td></tr></table>> shape=plaintext]
"101" [label=<<table><tr><td>ParentClass</td></tr></table>> shape=plaintext]
"102" -> "103" [dir=back arrowtail=none style=dashed]
"103" [label=<<table><tr><td>TestClass</td></tr></table>> shape=plaintext]
"101" -> "103" [dir=back arrowtail=empty style=solid]
"2" -> "103" [dir=back arrowtail=normal style=dashed]
"3" -> "103" [dir=back arrowtail=normal style=dashed]
"1" [label=<<table><tr><td>ParentInterface</td></tr></table>> shape=plaintext]
"2" [label=<<table><tr><td>ChildInterface</td></tr></table>> shape=plaintext]
"1" -> "2" [dir=back arrowtail=empty style=solid]
"3" [label=<<table><tr><td>AnotherInterface</td></tr></table>> shape=plaintext]
}', $dotLanguage);
    }
}
