<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class OutputFilePathTest extends TestCase
{
    /** @test */
    function it_knows_its_current_value()
    {
        $path = OutputFilePath::withExpectedExtension(__DIR__ . '/../../resources/.output/output.png', 'png');
        $trimmedPath = OutputFilePath::withExpectedExtension('  file.png  ', 'png');

        $this->assertStringEndsWith('/resources/.output/output.png', $path->value());
        $this->assertStringEndsWith('file.png', $trimmedPath->value());
    }

    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);

        OutputFilePath::withExpectedExtension('  ', 'txt');
    }
}
