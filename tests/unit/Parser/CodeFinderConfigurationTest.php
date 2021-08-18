<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CodeFinderConfigurationTest extends TestCase
{
    /** @test */
    function it_expects_find_recursively_option_to_be_a_boolean()
    {
        $options = ['recursive' => '1'];
        $this->expectException(InvalidArgumentException::class);
        new CodeFinderConfiguration($options);
    }
}
