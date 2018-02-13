<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

use RuntimeException;

class UnknownTheme extends RuntimeException
{
    public static function named(string $name, array $validNames): UnknownTheme
    {
        return new UnknownTheme(sprintf(
            'Invalid theme "%s" found, valid themes are: %s',
            $name,
            implode(', ', $validNames)
        ));
    }
}
