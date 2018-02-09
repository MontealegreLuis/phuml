<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use LogicException;
use PHPUnit\Framework\TestCase;
use PhUml\Fakes\ClassNameLabelBuilder;
use PhUml\Fakes\NumericIdStructureBuilder;
use PhUml\Fakes\ProvidesNumericIds;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\GraphvizProcessor;

class GenerateDotFileTest extends TestCase
{
    use ProvidesNumericIds;

    /** @test */
    function it_fails_to_generate_the_dot_file_if_a_command_is_not_provided()
    {
        $generator = new DotFileGenerator(new CodeParser(), new GraphvizProcessor());

        $this->expectException(LogicException::class);

        $generator->generate(new NonRecursiveCodeFinder(), 'wont-be-generated.gv');
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory()
    {
        $file = __DIR__ . '/../resources/.output/dot.gv';
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('"101" [label=<<table><tr><td>plBase</td></tr></table>> shape=plaintext]', $digraphInDotFormat);
        $this->assertContains('"102" [label=<<table><tr><td>plPhuml</td></tr></table>> shape=plaintext]', $digraphInDotFormat);
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory_using_a_recursive_finder()
    {
        $expectedDigraph = <<<'DOT'
"101" [label=<<table><tr><td>plBase</td></tr></table>> shape=plaintext]
"102" [label=<<table><tr><td>plStructureGenerator</td></tr></table>> shape=plaintext]
"103" [label=<<table><tr><td>plStructureTokenparserGenerator</td></tr></table>> shape=plaintext]
"102" -> "103" [dir=back arrowtail=empty style=solid]
"104" [label=<<table><tr><td>plPhpAttribute</td></tr></table>> shape=plaintext]
"105" [label=<<table><tr><td>plPhpClass</td></tr></table>> shape=plaintext]
"106" [label=<<table><tr><td>plPhpFunction</td></tr></table>> shape=plaintext]
"107" [label=<<table><tr><td>plPhpFunctionParameter</td></tr></table>> shape=plaintext]
"108" [label=<<table><tr><td>plPhpInterface</td></tr></table>> shape=plaintext]
"109" [label=<<table><tr><td>plPhuml</td></tr></table>> shape=plaintext]
"110" [label=<<table><tr><td>plExternalCommandProcessor</td></tr></table>> shape=plaintext]
"111" [label=<<table><tr><td>plDotProcessor</td></tr></table>> shape=plaintext]
"110" -> "111" [dir=back arrowtail=empty style=solid]
"112" [label=<<table><tr><td>plProcessor</td></tr></table>> shape=plaintext]
"113" [label=<<table><tr><td>plGraphvizProcessor</td></tr></table>> shape=plaintext]
"112" -> "113" [dir=back arrowtail=empty style=solid]
"114" [label=<<table><tr><td>plProcessorOptions</td></tr></table>> shape=plaintext]
"115" [label=<<table><tr><td>plGraphvizProcessorOptions</td></tr></table>> shape=plaintext]
"114" -> "115" [dir=back arrowtail=empty style=solid]
"116" [label=<<table><tr><td>plGraphvizProcessorStyle</td></tr></table>> shape=plaintext]
"117" [label=<<table><tr><td>plGraphvizProcessorDefaultStyle</td></tr></table>> shape=plaintext]
"116" -> "117" [dir=back arrowtail=empty style=solid]
"118" [label=<<table><tr><td>plNeatoProcessor</td></tr></table>> shape=plaintext]
"110" -> "118" [dir=back arrowtail=empty style=solid]
"119" [label=<<table><tr><td>plStatisticsProcessor</td></tr></table>> shape=plaintext]
"112" -> "119" [dir=back arrowtail=empty style=solid]
DOT;
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $finder = new CodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains($expectedDigraph, $digraphInDotFormat);
    }

    /** @before */
    function createGenerator()
    {
        $this->generator = new DotFileGenerator(
            new CodeParser(new NumericIdStructureBuilder()),
            new GraphvizProcessor(
                new ClassGraphBuilder(new ClassNameLabelBuilder()),
                new InterfaceGraphBuilder(new ClassNameLabelBuilder())
            )
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    /** @var DotFileGenerator */
    private $generator;
}
