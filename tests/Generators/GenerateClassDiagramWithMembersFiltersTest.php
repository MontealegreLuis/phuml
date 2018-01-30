<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\Builders\NoAttributesBuilder;
use PhUml\Parser\Raw\Builders\NoMethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\Php5Parser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;

class GenerateClassDiagramWithMembersFiltersTest extends TestCase
{
    use CompareImagesTrait;

    /** @before */
    function createGenerator()
    {
        $methodsBuilder = new NoMethodsBuilder();
        $this->generator = new ClassDiagramGenerator(
            new CodeParser(
                new StructureBuilder(),
                new Php5Parser(
                    new RawClassBuilder(new NoAttributesBuilder(), $methodsBuilder),
                    new RawInterfaceBuilder($methodsBuilder)
                )
            ),
            new GraphvizProcessor(),
            new DotProcessor()
        );
    }

    /**
     * @test
     * @group snapshot
     */
    function it_filters_attributes_and_methods()
    {
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-members-filtered.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-members-filtered.png';

        $this->generator->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @var ClassDiagramGenerator */
    private $generator;
}
