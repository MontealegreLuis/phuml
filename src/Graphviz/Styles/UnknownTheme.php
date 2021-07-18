<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

use RuntimeException;

final class UnknownTheme extends RuntimeException
{
    /** @param string[] $validNames */
    public static function named(string $name, array $validNames): UnknownTheme
    {
        return new UnknownTheme(sprintf(
            'Invalid theme "%s" found, valid themes are: %s',
            $name,
            implode(', ', $validNames)
        ));
    }
}
