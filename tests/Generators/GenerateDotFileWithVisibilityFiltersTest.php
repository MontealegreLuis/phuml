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
use PhUml\Parser\Raw\Builders\AttributesBuilder;
use PhUml\Parser\Raw\Builders\ConstantsBuilder;
use PhUml\Parser\Raw\Builders\Filters\MembersFilter;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;
use PhUml\Parser\Raw\Builders\MethodsBuilder;
use PhUml\Parser\Raw\Builders\RawClassBuilder;
use PhUml\Parser\Raw\Builders\RawInterfaceBuilder;
use PhUml\Parser\Raw\ParserBuilder;
use PhUml\Parser\Raw\Php5Parser;
use PhUml\Parser\Raw\PhpParser;
use PhUml\Parser\StructureBuilder;
use PhUml\Processors\GraphvizProcessor;

class GenerateDotFileWithVisibilityFiltersTest extends TestCase
{
    /** @test */
    function it_filters_private_members()
    {
        $this->createGenerator((new ParserBuilder())->excludePrivateMembers()->build());

        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('<table><tr><td>plBase</td></tr><tr><td>+autoload($classname: string): void<br/>+addAutoloadDirectory($directory: string): void<br/>+getAutoloadClasses(): string[]</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plPhuml</td></tr><tr><td>#$properties</td></tr><tr><td>+__construct()<br/>+addFile($file): void<br/>+addDirectory($directory, $extension, $recursive)<br/>+addProcessor($processor)<br/>+generate($outfile)<br/>+__get($key)<br/>+__set($key, $val)</td></tr></table>', $digraphInDotFormat);
    }

    /** @test */
    function it_filters_protected_members()
    {
        $this->createGenerator((new ParserBuilder())->excludeProtectedMembers()->build());

        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('<table><tr><td>plBase</td></tr><tr><td>-$autoload: array<br/>-$autoloadDirectory: string[]</td></tr><tr><td>+autoload($classname: string): void<br/>+addAutoloadDirectory($directory: string): void<br/>+getAutoloadClasses(): string[]</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plPhuml</td></tr><tr><td>-$files<br/>-$processors</td></tr><tr><td>+__construct()<br/>+addFile($file): void<br/>+addDirectory($directory, $extension, $recursive)<br/>+addProcessor($processor)<br/>-checkProcessorCompatibility($first, $second)<br/>+generate($outfile)<br/>+__get($key)<br/>+__set($key, $val)</td></tr></table>', $digraphInDotFormat);
    }

    /** @test */
    function it_filters_private_and_protected_members()
    {
        $this->createGenerator((new ParserBuilder())->excludePrivateMembers()->excludeProtectedMembers()->build());

        $finder = new NonRecursiveCodeFinder();
        $finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../resources/.code/classes'));
        $file = __DIR__ . '/../resources/.output/dot.gv';

        $this->generator->generate($finder, $file);

        $digraphInDotFormat = file_get_contents($file);
        $this->assertContains('<table><tr><td>plBase</td></tr><tr><td>+autoload($classname: string): void<br/>+addAutoloadDirectory($directory: string): void<br/>+getAutoloadClasses(): string[]</td></tr></table>', $digraphInDotFormat);
        $this->assertContains('<table><tr><td>plPhuml</td></tr><tr><td>+__construct()<br/>+addFile($file): void<br/>+addDirectory($directory, $extension, $recursive)<br/>+addProcessor($processor)<br/>+generate($outfile)<br/>+__get($key)<br/>+__set($key, $val)</td></tr></table>', $digraphInDotFormat);
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
