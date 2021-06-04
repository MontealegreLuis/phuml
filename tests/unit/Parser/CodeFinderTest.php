<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;

final class CodeFinderTest extends TestCase
{
    private $pathToCode;

    /** @test */
    function it_finds_files_only_in_the_given_directory()
    {
        $finder = new NonRecursiveCodeFinder();

        $finder->addDirectory(CodebaseDirectory::from("{$this->pathToCode}/classes"));

        $this->assertCount(2, $finder->files());
        $this->assertRegExp('/class plBase/', $finder->files()[0]);
        $this->assertRegExp('/class plPhuml/', $finder->files()[1]);
    }

    /** @test */
    function it_finds_files_recursively()
    {
        $finder = new CodeFinder();

        $finder->addDirectory(CodebaseDirectory::from("{$this->pathToCode}/interfaces"));

        $this->assertCount(7, $finder->files());
        $this->assertRegExp('/interface plCompatible/', $finder->files()[0]);
        $this->assertRegExp('/trait plDiskWriter/', $finder->files()[1]);
        $this->assertRegExp('/trait plFileWriter/', $finder->files()[2]);
        $this->assertRegExp('/abstract class plStructureGenerator/', $finder->files()[3]);
        $this->assertRegExp('/abstract class plProcessor/', $finder->files()[4]);
        $this->assertRegExp('/abstract class plExternalCommandProcessor/', $finder->files()[5]);
        $this->assertRegExp('/abstract class plGraphvizProcessorStyle/', $finder->files()[6]);
    }

    /** @before */
    function let()
    {
        $this->pathToCode = __DIR__ . '/../../resources/.code';
    }
}
