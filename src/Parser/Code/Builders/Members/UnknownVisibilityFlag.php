<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use RuntimeException;

final class UnknownVisibilityFlag extends RuntimeException
{
    public static function withValue(int $flags): UnknownVisibilityFlag
    {
        return new self("Unknown visibility flags with value ${flags}");
    }
}
