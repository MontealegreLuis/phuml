<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Fakes\SimpleTableLabelBuilder;
use PhUml\Graphviz\Builders\ClassGraphBuilder;
use PhUml\Graphviz\Builders\InterfaceGraphBuilder;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\ParserBuilder;
use PhUml\Parser\Raw\PhpParser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\GraphvizProcessor;

class GenerateDotFileWithMembersFiltersTest extends TestCase
{
    /** @test */
    function it_does_not_show_methods()
    {
        $this->createGenerator((new ParserBuilder())->excludeMethods()->build());

        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes/processor'));
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('<table><tr><td>plExternalCommandProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plDotProcessor</td></tr><tr><td>+$options</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plGraphvizProcessor</td></tr><tr><td>-$properties<br/>-$output<br/>-$structure<br/>+$options</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plNeatoProcessor</td></tr><tr><td>+$options</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plProcessorOptions</td></tr><tr><td>+BOOL: int<br/>+STRING: int<br/>+DECIMAL: int</td></tr><tr><td>#$properties</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plStatisticsProcessor</td></tr><tr><td>-$information<br/>+$options</td></tr></table>', $digraphInDotFormat);
    }

    /** @test */
    function it_does_not_show_attributes()
    {
        $this->createGenerator((new ParserBuilder())->excludeAttributes()->build());

        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes/processor'));
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('<table><tr><td>plExternalCommandProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plDotProcessor</td></tr><tr><td>+__construct()<br/>+getInputTypes()<br/>+getOutputType()<br/>+execute($infile, $outfile, $type)</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plGraphvizProcessor</td></tr><tr><td>+__construct()<br/>+getInputTypes()<br/>+getOutputType()<br/>+process($input, $type)<br/>-getClassDefinition($o)<br/>-getInterfaceDefinition($o)<br/>-getModifierRepresentation($modifier)<br/>-getParamRepresentation($params)<br/>-getUniqueId($object)<br/>-createNode($name, $options)<br/>-createNodeRelation($node1, $node2, $options)<br/>-createInterfaceLabel($name, $attributes, $functions)<br/>-createClassLabel($name, $attributes, $functions)</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plNeatoProcessor</td></tr><tr><td>+__construct()<br/>+getInputTypes()<br/>+getOutputType()<br/>+execute($infile, $outfile, $type)</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plProcessorOptions</td></tr><tr><td>+__get($key)<br/>+__set($key, $val)<br/>+getOptions()<br/>+getOptionDescription($option)<br/>+getOptionType($option)</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plStatisticsProcessor</td></tr><tr><td>+__construct()<br/>+getInputTypes()<br/>+getOutputType()<br/>+process($input, $type)</td></tr></table>', $digraphInDotFormat);
    }

    /** @test */
    function it_shows_only_names()
    {
        $this->createGenerator((new ParserBuilder())->excludeAttributes()->excludeMethods()->build());

        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes/processor'));
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('<table><tr><td>plExternalCommandProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plDotProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plGraphvizProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plNeatoProcessor</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plProcessorOptions</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plStatisticsProcessor</td></tr></table>', $digraphInDotFormat);
    }

    function createGenerator(PhpParser $parser): void
    {
        $this->generator = new DotFileGenerator(
            new CodeParser(new StructureBuilder(), $parser),
            new GraphvizProcessor(
                new ClassGraphBuilder(new SimpleTableLabelBuilder()),
                new InterfaceGraphBuilder(new SimpleTableLabelBuilder())
            )
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    /** @var DotFileGenerator */
    private $generator;
}
