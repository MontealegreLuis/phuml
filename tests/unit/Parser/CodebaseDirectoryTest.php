<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;

final class CodebaseDirectoryTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidDirectory::class);
        new CodebaseDirectory('    ');
    }
}
