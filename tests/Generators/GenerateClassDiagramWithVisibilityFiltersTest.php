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
use PhUml\Parser\Raw\Builders\AttributesBuilder;
use PhUml\Parser\Raw\Builders\ConstantsBuilder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\MethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\Php5Parser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;

class GenerateClassDiagramWithVisibilityFiltersTest extends TestCase
{
    use CompareImagesTrait;

    /** @param MembersFilter[] */
    function createGenerator(array $filters)
    {
        $methodsBuilder = new MethodsBuilder($filters);
        $this->generator = new ClassDiagramGenerator(
            new CodeParser(
                new StructureBuilder(),
                new Php5Parser(
                    new RawClassBuilder(new ConstantsBuilder(), new AttributesBuilder($filters), $methodsBuilder),
                    new RawInterfaceBuilder(new ConstantsBuilder(), $methodsBuilder))
            ),
            new GraphvizProcessor(),
            new DotProcessor()
        );
    }

    /**
     * @test
     * @group snapshot
     */
    function it_filters_private_and_protected_methods()
    {
        $this->createGenerator([new PrivateMembersFilter(), new ProtectedMembersFilter()]);

        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-filtered.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-filtered.png';

        $this->generator->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @var ClassDiagramGenerator */
    private $generator;
}
