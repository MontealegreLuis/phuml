<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PHPUnit\Framework\TestCase;
use PhUml\Fakes\WithVisibilityAssertions;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\SourceCodeFinder;
use PhUml\TestBuilders\A;

final class PhpCodeParserTest extends TestCase
{
    use WithVisibilityAssertions;

    /** @test */
    function it_excludes_methods()
    {
        $configuration = A::codeParserConfiguration()->withoutMethods()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->methods());
        $this->assertNotEmpty($definitions['plBase']->attributes());
        $this->assertEmpty($definitions['plPhuml']->methods());
        $this->assertNotEmpty($definitions['plPhuml']->attributes());
    }

    /** @test */
    function it_excludes_attributes()
    {
        $configuration = A::codeParserConfiguration()->withoutAttributes()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertNotEmpty($definitions['plBase']->methods());
        $this->assertEmpty($definitions['plPhuml']->attributes());
        $this->assertNotEmpty($definitions['plPhuml']->methods());
    }

    /** @test */
    function it_excludes_both_methods_and_attributes()
    {
        $configuration = A::codeParserConfiguration()->withoutMethods()->withoutAttributes()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertEmpty($definitions['plBase']->methods());
        $this->assertEmpty($definitions['plPhuml']->attributes());
        $this->assertEmpty($definitions['plPhuml']->methods());
    }

    /** @test */
    function it_excludes_private_members()
    {
        $configuration = A::codeParserConfiguration()->withoutPrivateMembers()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertCount(3, $definitions['plBase']->methods());
        $this->assertPublic($definitions['plBase']->methods()[0]);
        $this->assertPublic($definitions['plBase']->methods()[1]);
        $this->assertPublic($definitions['plBase']->methods()[2]);
        $this->assertCount(1, $definitions['plPhuml']->attributes());
        $this->assertProtected($definitions['plPhuml']->attributes()[0]);
        $this->assertCount(7, $definitions['plPhuml']->methods());
        $this->assertPublic($definitions['plPhuml']->methods()[0]);
        $this->assertPublic($definitions['plPhuml']->methods()[1]);
        $this->assertPublic($definitions['plPhuml']->methods()[2]);
        $this->assertPublic($definitions['plPhuml']->methods()[3]);
        $this->assertPublic($definitions['plPhuml']->methods()[5]);
        $this->assertPublic($definitions['plPhuml']->methods()[6]);
        $this->assertPublic($definitions['plPhuml']->methods()[7]);
    }

    /** @test */
    function it_excludes_protected_members()
    {
        $configuration = A::codeParserConfiguration()->withoutProtectedMembers()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertCount(2, $definitions['plBase']->attributes());
        $this->assertPrivate($definitions['plBase']->attributes()[0]);
        $this->assertPrivate($definitions['plBase']->attributes()[1]);
        $this->assertCount(3, $definitions['plBase']->methods());
        $this->assertPublic($definitions['plBase']->methods()[0]);
        $this->assertPublic($definitions['plBase']->methods()[1]);
        $this->assertPublic($definitions['plBase']->methods()[2]);
        $this->assertCount(2, $definitions['plPhuml']->attributes());
        $this->assertPrivate($definitions['plPhuml']->attributes()[1]);
        $this->assertPrivate($definitions['plPhuml']->attributes()[2]);
        $this->assertCount(8, $definitions['plPhuml']->methods());
        $this->assertPublic($definitions['plPhuml']->methods()[0]);
        $this->assertPublic($definitions['plPhuml']->methods()[1]);
        $this->assertPublic($definitions['plPhuml']->methods()[2]);
        $this->assertPublic($definitions['plPhuml']->methods()[3]);
        $this->assertPrivate($definitions['plPhuml']->methods()[4]);
        $this->assertPublic($definitions['plPhuml']->methods()[5]);
        $this->assertPublic($definitions['plPhuml']->methods()[6]);
        $this->assertPublic($definitions['plPhuml']->methods()[7]);
    }

    /** @test */
    function it_excludes_private_and_protected_members()
    {
        $configuration = A::codeParserConfiguration()->withoutProtectedMembers()->withoutPrivateMembers()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['plBase']->attributes());
        $this->assertCount(3, $definitions['plBase']->methods());
        $this->assertPublic($definitions['plBase']->methods()[0]);
        $this->assertPublic($definitions['plBase']->methods()[1]);
        $this->assertPublic($definitions['plBase']->methods()[2]);
        $this->assertEmpty($definitions['plPhuml']->attributes());
        $this->assertCount(7, $definitions['plPhuml']->methods());
        $this->assertPublic($definitions['plPhuml']->methods()[0]);
        $this->assertPublic($definitions['plPhuml']->methods()[1]);
        $this->assertPublic($definitions['plPhuml']->methods()[2]);
        $this->assertPublic($definitions['plPhuml']->methods()[3]);
        $this->assertPublic($definitions['plPhuml']->methods()[5]);
        $this->assertPublic($definitions['plPhuml']->methods()[6]);
        $this->assertPublic($definitions['plPhuml']->methods()[7]);
    }

    /** @before */
    function let()
    {
        $this->directory = new CodebaseDirectory(__DIR__ . '/../../../resources/.code/classes');
        $this->finder = SourceCodeFinder::nonRecursive();
    }

    private CodeFinder $finder;

    private CodebaseDirectory $directory;
}
