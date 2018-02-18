<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw;

use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\NonRecursiveCodeFinder;

class Php5ParserTest extends TestCase
{
    /** @test */
    function it_excludes_methods()
    {
        $parser = (new ParserBuilder())->excludeMethods()->build();

        $definitions = $parser->parse($this->finder)->all();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->methods());
        $this->assertNotEmpty($definitions['plBase']->attributes());
        $this->assertEmpty($definitions['plPhuml']->methods());
        $this->assertNotEmpty($definitions['plPhuml']->attributes());
    }

    /** @test */
    function it_excludes_attributes()
    {
        $parser = (new ParserBuilder())->excludeAttributes()->build();

        $definitions = $parser->parse($this->finder)->all();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertNotEmpty($definitions['plBase']->methods());
        $this->assertEmpty($definitions['plPhuml']->attributes());
        $this->assertNotEmpty($definitions['plPhuml']->methods());
    }

    /** @test */
    function it_excludes_both_methods_and_attributes()
    {
        $parser = (new ParserBuilder())->excludeAttributes()->excludeMethods()->build();

        $definitions = $parser->parse($this->finder)->all();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertEmpty($definitions['plBase']->methods());
        $this->assertEmpty($definitions['plPhuml']->attributes());
        $this->assertEmpty($definitions['plPhuml']->methods());
    }

    /** @test */
    function it_excludes_private_members()
    {
        $modifiers = ['protected', 'public'];
        $parser = (new ParserBuilder())->excludePrivateMembers()->build();

        $definitions = $parser->parse($this->finder)->all();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertCount(3, $definitions['plBase']->methods());
        $this->assertContains($definitions['plBase']->methods()[0][1], $modifiers);
        $this->assertContains($definitions['plBase']->methods()[1][1], $modifiers);
        $this->assertContains($definitions['plBase']->methods()[2][1], $modifiers);
        $this->assertCount(1, $definitions['plPhuml']->attributes());
        $this->assertTrue($definitions['plPhuml']->attributes()[0]->isProtected());
        $this->assertCount(7, $definitions['plPhuml']->methods());
        $this->assertContains($definitions['plPhuml']->methods()[0][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[1][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[2][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[3][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[5][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[6][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[7][1], $modifiers);
    }

    /** @test */
    function it_excludes_protected_members()
    {
        $modifiers = ['private', 'public'];
        $parser = (new ParserBuilder())->excludeProtectedMembers()->build();

        $definitions = $parser->parse($this->finder)->all();

        $this->assertCount(2, $definitions);
        $this->assertCount(2, $definitions['plBase']->attributes());
        $this->assertTrue($definitions['plBase']->attributes()[0]->isPrivate());
        $this->assertTrue($definitions['plBase']->attributes()[1]->isPrivate());
        $this->assertCount(3, $definitions['plBase']->methods());
        $this->assertContains($definitions['plBase']->methods()[0][1], $modifiers);
        $this->assertContains($definitions['plBase']->methods()[1][1], $modifiers);
        $this->assertContains($definitions['plBase']->methods()[2][1], $modifiers);
        $this->assertCount(2, $definitions['plPhuml']->attributes());
        $this->assertTrue($definitions['plPhuml']->attributes()[1]->isPrivate());
        $this->assertTrue($definitions['plPhuml']->attributes()[2]->isPrivate());
        $this->assertCount(8, $definitions['plPhuml']->methods());
        $this->assertContains($definitions['plPhuml']->methods()[0][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[1][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[2][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[3][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[4][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[5][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[6][1], $modifiers);
        $this->assertContains($definitions['plPhuml']->methods()[7][1], $modifiers);
    }

    /** @test */
    function it_excludes_private_and_protected_members()
    {
        $parser = (new ParserBuilder())->excludeProtectedMembers()->excludePrivateMembers()->build();

        $definitions = $parser->parse($this->finder)->all();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertCount(3, $definitions['plBase']->methods());
        $this->assertEquals('public', $definitions['plBase']->methods()[0][1]);
        $this->assertEquals('public', $definitions['plBase']->methods()[1][1]);
        $this->assertEquals('public', $definitions['plBase']->methods()[2][1]);
        $this->assertEmpty($definitions['plPhuml']->attributes());
        $this->assertCount(7, $definitions['plPhuml']->methods());
        $this->assertEquals('public', $definitions['plPhuml']->methods()[0][1]);
        $this->assertEquals('public', $definitions['plPhuml']->methods()[1][1]);
        $this->assertEquals('public', $definitions['plPhuml']->methods()[2][1]);
        $this->assertEquals('public', $definitions['plPhuml']->methods()[3][1]);
        $this->assertEquals('public', $definitions['plPhuml']->methods()[5][1]);
        $this->assertEquals('public', $definitions['plPhuml']->methods()[6][1]);
        $this->assertEquals('public', $definitions['plPhuml']->methods()[7][1]);
    }

    /** @before */
    function createFinder()
    {
        $this->finder = new NonRecursiveCodeFinder();
        $this->finder->addDirectory(CodebaseDirectory::from(__DIR__ . '/../../resources/.code/classes'));
    }

    /** @var NonRecursiveCodeFinder */
    private $finder;
}
