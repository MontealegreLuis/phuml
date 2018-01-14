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
use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;

class GenerateClassDiagramTest extends TestCase
{
    use CompareImagesTrait;

    /** @before */
    function createAction()
    {
        $this->action = new GenerateClassDiagram(new TokenParser(), new GraphvizProcessor());
    }

    /** @test */
    function it_fails_to_generate_diagram_if_command_is_not_provided()
    {
        $this->expectException(LogicException::class);
        $this->action->generate(new CodeFinder(), 'wont-be-generated.png');
    }

    /** @test */
    function it_fails_to_generate_diagram_if_no_image_processor_is_provided()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());

        $this->expectException(LogicException::class);
        $this->action->generate(new CodeFinder(), 'wont-be-generated.png');
    }

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_the_dot_processor()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());
        $this->action->setImageProcessor(new DotProcessor());
        $finder = new CodeFinder();
        $finder->addDirectory(__DIR__ . '/../resources/.code/classes', false);
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-linux.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-linux.png';

        $this->action->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_the_dot_processors_and_the_recursive_option()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());
        $this->action->setImageProcessor(new DotProcessor());
        $finder = new CodeFinder();
        $finder->addDirectory(__DIR__ . '/../resources/.code');
        $diagram = __DIR__ . '/../resources/.output/graphviz-dot-recursive-linux.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-dot-recursive-linux.png';

        $this->action->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_the_neato_processor()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());
        $this->action->setImageProcessor(new NeatoProcessor());
        $finder = new CodeFinder();
        $finder->addDirectory(__DIR__ . '/../resources/.code/classes', false);
        $diagram = __DIR__ . '/../resources/.output/graphviz-neato-linux.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-neato-linux.png';

        $this->action->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /**
     * @test
     * @group linux
     */
    function it_generates_a_class_diagram_using_the_neato_processors_and_the_recursive_option()
    {
        $this->action->attach($this->prophesize(CanExecuteAction::class)->reveal());
        $this->action->setImageProcessor(new NeatoProcessor());
        $finder = new CodeFinder();
        $finder->addDirectory(__DIR__ . '/../resources/.code');
        $diagram = __DIR__ . '/../resources/.output/graphviz-neato-recursive-linux.png';
        $expectedDiagram = __DIR__ . '/../resources/images/graphviz-neato-recursive-linux.png';

        $this->action->generate($finder, $diagram);

        $this->assertImagesSame($expectedDiagram, $diagram);
    }

    /** @var GenerateClassDiagram */
    private $action;
}
