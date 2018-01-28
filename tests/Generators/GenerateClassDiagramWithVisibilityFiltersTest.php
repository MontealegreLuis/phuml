<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\Builders\AttributesBuilder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\MethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\ExternalDefinitionsResolver;
use PhUml\Parser\Raw\RawDefinitions;
use PhUml\Parser\Raw\TokenParser;
use PhUml\Parser\Raw\Visitors\ClassVisitor;
use PhUml\Parser\Raw\Visitors\InterfaceVisitor;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;

class GenerateClassDiagramWithVisibilityFiltersTest extends TestCase
{
    use CompareImagesTrait;

    /** @param MembersFilter[] */
    function createGenerator(array $filters)
    {
        $attributesBuilder = new AttributesBuilder($filters);
        $methodsBuilder = new MethodsBuilder($filters);
        $definitions = new RawDefinitions();
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new ClassVisitor(
            $definitions,
            new RawClassBuilder($attributesBuilder, $methodsBuilder)
        ));
        $traverser->addVisitor(new InterfaceVisitor(
            $definitions,
            new RawInterfaceBuilder($methodsBuilder)
        ));
        $this->generator = new ClassDiagramGenerator(
            new CodeParser(
                new StructureBuilder(),
                new TokenParser(
                    (new ParserFactory)->create(ParserFactory::PREFER_PHP5),
                    $traverser,
                    $definitions
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
