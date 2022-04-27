<?php declare(strict_types=1);
/**
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
        $this->assertNotEmpty($definitions['phuml\\plBase']->properties());
        $this->assertEmpty($definitions['phuml\\plPhuml']->methods());
        $this->assertNotEmpty($definitions['phuml\\plPhuml']->properties());
    }

    /** @test */
    function it_excludes_properties()
    {
        $configuration = A::codeParserConfiguration()->withoutAttributes()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['phuml\\plBase']->properties());
        $this->assertNotEmpty($definitions['phuml\\plBase']->methods());
        $this->assertEmpty($definitions['phuml\\plPhuml']->properties());
        $this->assertNotEmpty($definitions['phuml\\plPhuml']->methods());
    }

    /** @test */
    function it_excludes_both_methods_and_properties()
    {
        $configuration = A::codeParserConfiguration()->withoutMethods()->withoutAttributes()->build();
        $parser = PhpCodeParser::fromConfiguration($configuration);
        $sourceCode = $this->finder->find($this->directory);

        $definitions = $parser->parse($sourceCode)->definitions();

        $this->assertCount(2, $definitions);
        $this->assertEmpty($definitions['phuml\\plBase']->properties());
        $this->assertEmpty($definitions['phuml\\plBase']->methods());
        $this->assertEmpty($definitions['phuml\\plPhuml']->properties());
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
        $this->assertEmpty($definitions['phuml\\plBase']->properties());
        $this->assertCount(3, $definitions['phuml\\plBase']->methods());
        $this->assertPublic($definitions['phuml\\plBase']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[2]);
        $this->assertCount(1, $definitions['phuml\\plPhuml']->properties());
        $this->assertProtected($definitions['phuml\\plPhuml']->properties()[0]);
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
        $this->assertCount(2, $definitions['phuml\\plBase']->properties());
        $this->assertPrivate($definitions['phuml\\plBase']->properties()[0]);
        $this->assertPrivate($definitions['phuml\\plBase']->properties()[1]);
        $this->assertCount(3, $definitions['phuml\\plBase']->methods());
        $this->assertPublic($definitions['phuml\\plBase']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[2]);
        $this->assertCount(2, $definitions['phuml\\plPhuml']->properties());
        $this->assertPrivate($definitions['phuml\\plPhuml']->properties()[0]);
        $this->assertPrivate($definitions['phuml\\plPhuml']->properties()[1]);
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
        $this->assertEmpty($definitions['phuml\\plBase']->properties());
        $this->assertCount(3, $definitions['phuml\\plBase']->methods());
        $this->assertPublic($definitions['phuml\\plBase']->methods()[0]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[1]);
        $this->assertPublic($definitions['phuml\\plBase']->methods()[2]);
        $this->assertEmpty($definitions['phuml\\plPhuml']->properties());
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
