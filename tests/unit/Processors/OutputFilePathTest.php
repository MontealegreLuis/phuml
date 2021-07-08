<?php declare(strict_types=1);
/**
 * PHP version 7.4
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
        $path = new OutputFilePath(__DIR__ . '/../../resources/.output/output.png');

        $this->assertStringEndsWith('/resources/.output/output.png', $path->value());
    }

    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);

        new OutputFilePath('  ');
    }
}
