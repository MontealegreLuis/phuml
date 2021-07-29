<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;

final class CodeFinderTest extends TestCase
{
    /** @test */
    function it_finds_files_only_in_the_given_directory()
    {
        $finder = SourceCodeFinder::fromConfiguration(new CodeFinderConfiguration(['recursive' => false]));

        $sourceCode = $finder->find(new CodebaseDirectory("{$this->pathToCode}/classes"));

        $this->assertCount(2, $sourceCode->fileContents());
        $this->assertMatchesRegularExpression('/class plBase/', $sourceCode->fileContents()[0]);
        $this->assertMatchesRegularExpression('/class plPhuml/', $sourceCode->fileContents()[1]);
    }

    /** @test */
    function it_finds_files_recursively()
    {
        $finder = SourceCodeFinder::fromConfiguration(new CodeFinderConfiguration(['recursive' => true]));

        $sourceCode = $finder->find(new CodebaseDirectory("{$this->pathToCode}/interfaces"));

        $this->assertCount(7, $sourceCode->fileContents());
        $this->assertMatchesRegularExpression('/interface plCompatible/', $sourceCode->fileContents()[0]);
        $this->assertMatchesRegularExpression('/trait plDiskWriter/', $sourceCode->fileContents()[1]);
        $this->assertMatchesRegularExpression('/trait plFileWriter/', $sourceCode->fileContents()[2]);
        $this->assertMatchesRegularExpression('/abstract class plStructureGenerator/', $sourceCode->fileContents()[3]);
        $this->assertMatchesRegularExpression('/abstract class plProcessor/', $sourceCode->fileContents()[4]);
        $this->assertMatchesRegularExpression(
            '/abstract class plExternalCommandProcessor/',
            $sourceCode->fileContents()[5]
        );
        $this->assertMatchesRegularExpression(
            '/abstract class plGraphvizProcessorStyle/',
            $sourceCode->fileContents()[6]
        );
    }

    /** @before */
    function let()
    {
        $this->pathToCode = __DIR__ . '/../../resources/.code';
    }

    private string $pathToCode;
}
