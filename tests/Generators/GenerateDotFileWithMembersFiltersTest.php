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
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plExternalCommandProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plDotProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+$options</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plGraphvizProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-$properties</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-$output</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-$structure</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+$options</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plNeatoProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+$options</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plProcessorOptions</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+BOOL: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+STRING: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10"><I>+DECIMAL: int</I></FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">#$properties</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plStatisticsProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-$information</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+$options</FONT><BR ALIGN="LEFT"/></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
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
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plExternalCommandProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plDotProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getInputTypes()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOutputType()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+execute($infile, $outfile, $type)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plGraphvizProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getInputTypes()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOutputType()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+process($input, $type)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-getClassDefinition($o)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-getInterfaceDefinition($o)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-getModifierRepresentation($modifier)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-getParamRepresentation($params)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-getUniqueId($object)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-createNode($name, $options)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-createNodeRelation($node1, $node2, $options)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-createInterfaceLabel($name, $attributes, $functions)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">-createClassLabel($name, $attributes, $functions)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plNeatoProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getInputTypes()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOutputType()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+execute($infile, $outfile, $type)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plProcessorOptions</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__get($key)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__set($key, $val)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOptions()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOptionDescription($option)</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOptionType($option)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plStatisticsProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec"><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+__construct()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getInputTypes()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+getOutputType()</FONT><BR ALIGN="LEFT"/><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="10">+process($input, $type)</FONT><BR ALIGN="LEFT"/></TD></TR></TABLE>', $digraphInDotFormat);
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
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plExternalCommandProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plDotProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plGraphvizProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plNeatoProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plProcessorOptions</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
        $this->assertContains('<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT"><TR><TD BORDER="1" ALIGN="CENTER" BGCOLOR="#fcaf3e"><B><FONT COLOR="#2e3436" FACE="Helvetica" POINT-SIZE="12">plStatisticsProcessor</FONT></B></TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR><TR><TD BORDER="1" ALIGN="LEFT" BGCOLOR="#eeeeec">&nbsp;</TD></TR></TABLE>', $digraphInDotFormat);
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
