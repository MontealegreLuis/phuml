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
use PhUml\Fakes\ClassNameLabelBuilder;
use PhUml\Fakes\NumericIdClass;
use PhUml\Fakes\NumericIdInterface;
use PhUml\Fakes\ProvidesNumericIds;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\EdgesBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;

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
        $labelBuilder = new ClassNameLabelBuilder();
        $processor = new GraphvizProcessor(
            new ClassGraphBuilder($labelBuilder, new EdgesBuilder()),
            new InterfaceGraphBuilder($labelBuilder)
        );

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
"101" [label=<<table><tr><td>ParentClass</td></tr></table>> shape=plaintext]
"102" [label=<<table><tr><td>ReferencedClass</td></tr></table>> shape=plaintext]
"102" -> "103" [dir=back arrowtail=none style=dashed]
"103" [label=<<table><tr><td>MyClass</td></tr></table>> shape=plaintext]
"101" -> "103" [dir=back arrowtail=empty style=solid]
"2" -> "103" [dir=back arrowtail=normal style=dashed]
"1" [label=<<table><tr><td>ParentInterface</td></tr></table>> shape=plaintext]
"2" [label=<<table><tr><td>ImplementedInterface</td></tr></table>> shape=plaintext]
"1" -> "2" [dir=back arrowtail=empty style=solid]
}', $dotLanguage);
    }
}
