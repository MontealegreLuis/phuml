<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use RuntimeException;
use SplFileInfo;

final class InvalidDirectory extends RuntimeException
{
    public static function notFoundAt(SplFileInfo $path): InvalidDirectory
    {
        return new InvalidDirectory("'$path' is not a directory or it cannot be found");
    }
}
