<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\NonRecursiveCodeFinder;
use PhUml\Parser\Raw\ParserBuilder;
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
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plBase</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+autoload($classname: string): void</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+addAutoloadDirectory($directory: string): void</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+getAutoloadClasses(): string[]</U></FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plPhuml</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#$properties</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addFile($file): void</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addDirectory($directory, $extension, $recursive)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addProcessor($processor)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+generate($outfile)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__get($key)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__set($key, $val)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
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
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plBase</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>-$autoload: array</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>-$autoloadDirectory: string[]</U></FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+autoload($classname: string): void</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+addAutoloadDirectory($directory: string): void</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+getAutoloadClasses(): string[]</U></FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plPhuml</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-$files</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-$processors</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addFile($file): void</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addDirectory($directory, $extension, $recursive)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addProcessor($processor)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-checkProcessorCompatibility($first, $second)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+generate($outfile)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__get($key)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__set($key, $val)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
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
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plBase</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+autoload($classname: string): void</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+addAutoloadDirectory($directory: string): void</U></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><U>+getAutoloadClasses(): string[]</U></FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plPhuml</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addFile($file): void</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addDirectory($directory, $extension, $recursive)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+addProcessor($processor)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+generate($outfile)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__get($key)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__set($key, $val)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
    }

    function createGenerator(PhpParser $parser): void
    {
        $this->generator = new DotFileGenerator(
            new CodeParser(new StructureBuilder(), $parser),
            new GraphvizProcessor()
        );
        $this->generator->attach($this->prophesize(ProcessorProgressDisplay::class)->reveal());
    }

    /** @var DotFileGenerator */
    private $generator;
}
