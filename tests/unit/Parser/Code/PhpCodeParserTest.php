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
use PhUml\Parser\CodeFinderConfiguration;
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
        $this->assertEmpty($definitions['phuml\\plBase']->methods());
        $this->assertNotEmpty($definitions['phuml\\plBase']->attributes());
        $this->assertEmpty($definitions['phuml\\plPhuml']->methods());
        $this->assertNotEmpty($definitions['phuml\\plPhuml']->attributes());
    }

    /** @test */
    function it_excludes_attributes()
    {
        $configuration = A::codeParserConfiguration()->withoutAttributes()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['phuml\\plBase']->attributes());
        $this->assertNotEmpty($definitions['phuml\\plBase']->methods());
        $this->assertEmpty($definitions['phuml\\plPhuml']->attributes());
        $this->assertNotEmpty($definitions['phuml\\plPhuml']->methods());
    }

    /** @test */
    function it_excludes_both_methods_and_attributes()
    {
        $configuration = A::codeParserConfiguration()->withoutMethods()->withoutAttributes()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['phuml\\plBase']->attributes());
        $this->assertEmpty($definitions['phuml\\plBase']->methods());
        $this->assertEmpty($definitions['phuml\\plPhuml']->attributes());
        $this->assertEmpty($definitions['phuml\\plPhuml']->methods());
    }

    /** @test */
    function it_excludes_private_members()
    {
        $configuration = A::codeParserConfiguration()->withoutPrivateMembers()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertCount(2, $definitions['phuml\\plBase']->constants());
        $this->assertProtected($definitions['phuml\\plBase']->constants()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->constants()[2]);
        $this->assertEmpty($definitions['phuml\\plBase']->attributes());
        $this->assertCount(3, $definitions['phuml\\plBase']->methods());
        $this->assertPublic($definitions['phuml\\plBase']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[2]);
        $this->assertCount(1, $definitions['phuml\\plPhuml']->attributes());
        $this->assertProtected($definitions['phuml\\plPhuml']->attributes()[0]);
        $this->assertCount(7, $definitions['phuml\\plPhuml']->methods());
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[2]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[3]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[5]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[6]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[7]);
    }

    /** @test */
    function it_excludes_protected_members()
    {
        $configuration = A::codeParserConfiguration()->withoutProtectedMembers()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertCount(2, $definitions['phuml\\plBase']->constants());
        $this->assertPrivate($definitions['phuml\\plBase']->constants()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->constants()[2]);
        $this->assertCount(2, $definitions['phuml\\plBase']->attributes());
        $this->assertPrivate($definitions['phuml\\plBase']->attributes()[0]);
        $this->assertPrivate($definitions['phuml\\plBase']->attributes()[1]);
        $this->assertCount(3, $definitions['phuml\\plBase']->methods());
        $this->assertPublic($definitions['phuml\\plBase']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[2]);
        $this->assertCount(2, $definitions['phuml\\plPhuml']->attributes());
        $this->assertPrivate($definitions['phuml\\plPhuml']->attributes()[0]);
        $this->assertPrivate($definitions['phuml\\plPhuml']->attributes()[1]);
        $this->assertCount(8, $definitions['phuml\\plPhuml']->methods());
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[2]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[3]);
        $this->assertPrivate($definitions['phuml\\plPhuml']->methods()[4]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[5]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[6]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[7]);
    }

    /** @test */
    function it_excludes_private_and_protected_members()
    {
        $configuration = A::codeParserConfiguration()->withoutProtectedMembers()->withoutPrivateMembers()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertCount(1, $definitions['phuml\\plBase']->constants());
        $this->assertPublic($definitions['phuml\\plBase']->constants()[2]);
        $this->assertEmpty($definitions['phuml\\plBase']->attributes());
        $this->assertCount(3, $definitions['phuml\\plBase']->methods());
        $this->assertPublic($definitions['phuml\\plBase']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[2]);
        $this->assertEmpty($definitions['phuml\\plPhuml']->attributes());
        $this->assertCount(7, $definitions['phuml\\plPhuml']->methods());
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[2]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[3]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[5]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[6]);
        $this->assertPublic($definitions['phuml\\plPhuml']->methods()[7]);
    }

    /** @before */
    function let()
    {
        $this->directory = new CodebaseDirectory(__DIR__ . '/../../../resources/.code/classes');
        $this->finder = SourceCodeFinder::fromConfiguration(new CodeFinderConfiguration(['recursive' => false]));
    }

    private CodeFinder $finder;

    private CodebaseDirectory $directory;
}
