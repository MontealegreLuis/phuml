<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;

final class CodeFinderTest extends TestCase
{
    private ?string $pathToCode = null;

    /** @test */
    function it_finds_files_only_in_the_given_directory()
    {
        $finder = SourceCodeFinder::nonRecursive(new CodebaseDirectory("{$this->pathToCode}/classes"));

        $this->assertCount(2, $finder->files());
        $this->assertMatchesRegularExpression('/class plBase/', $finder->files()[0]);
        $this->assertMatchesRegularExpression('/class plPhuml/', $finder->files()[1]);
    }

    /** @test */
    function it_finds_files_recursively()
    {
        $finder = SourceCodeFinder::recursive(new CodebaseDirectory("{$this->pathToCode}/interfaces"));

        $this->assertCount(7, $finder->files());
        $this->assertMatchesRegularExpression('/interface plCompatible/', $finder->files()[0]);
        $this->assertMatchesRegularExpression('/trait plDiskWriter/', $finder->files()[1]);
        $this->assertMatchesRegularExpression('/trait plFileWriter/', $finder->files()[2]);
        $this->assertMatchesRegularExpression('/abstract class plStructureGenerator/', $finder->files()[3]);
        $this->assertMatchesRegularExpression('/abstract class plProcessor/', $finder->files()[4]);
        $this->assertMatchesRegularExpression('/abstract class plExternalCommandProcessor/', $finder->files()[5]);
        $this->assertMatchesRegularExpression('/abstract class plGraphvizProcessorStyle/', $finder->files()[6]);
    }

    /** @before */
    function let()
    {
        $this->pathToCode = __DIR__ . '/../../resources/.code';
    }
}
