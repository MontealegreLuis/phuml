<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

final class CodeFinderConfiguration
{
    private bool $recursive;

    /** @param mixed[] $options */
    public function __construct(array $options)
    {
        $this->recursive = (bool) ($options['recursive'] ?? false);
    }

    public function recursive(): bool
    {
        return $this->recursive;
    }
}
