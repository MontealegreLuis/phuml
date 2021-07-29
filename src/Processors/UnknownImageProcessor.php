<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use RuntimeException;

final class UnknownImageProcessor extends RuntimeException
{
    /** @param string[] $validNames */
    public static function named(?string $name, array $validNames): UnknownImageProcessor
    {
        return new UnknownImageProcessor($name, $validNames);
    }

    /** @param string[] $validNames */
    public function __construct(?string $name, array $validNames)
    {
        parent::__construct(sprintf(
            'Invalid processor "%s" found, expected processors are: %s',
            $name,
            implode(', ', $validNames)
        ));
    }
}
