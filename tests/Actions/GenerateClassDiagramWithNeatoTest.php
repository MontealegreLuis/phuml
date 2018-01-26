<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use LogicException;
use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;

class GenerateClassDiagramWithNeatoTest extends TestCase
{
    use CompareImagesTrait;

    /** @before */
    function createAction()
    {
        $this->action = new GenerateClassDiagram(
            new CodeParser(),
            new GraphvizProcessor(),
            new NeatoProcessor()
        );
    }

    /** @test */
    function it_fails_to_generate_diagram_if_a_command_is_not_provided()
    {
        $this->expectException(LogicException::class);
        $this->action->generate(new NonRecursiveCodeFinder(), 'wont-be-generated.png');
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());
        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-neato.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-neato.png';

        $this->action->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group snapshot
     */
    function it_generates_a_class_diagram_using_a_recursive_finder()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());
        $codeFinder = new CodeFinder();
        $codeFinder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code'));
        $diagram = __DIR__ . '/../resources/.output/graphviz-neato-recursive.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-neato-recursive.png';

        $this->action->generate($codeFinder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @var GenerateClassDiagram */
    private $action;
}
