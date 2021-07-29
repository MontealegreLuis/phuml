<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class VisibilityTest extends TestCase
{
    /** @test */
    function it_prevents_creating_an_invalid_visibility_modifier()
    {
        $this->expectException(InvalidArgumentException::class);
        new Visibility('not a visibility modifier');
    }
}
