<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use Webmozart\Assert\Assert;

final class CodeFinderConfiguration
{
    private bool $recursive;

    /** @param mixed[] $options */
    public function __construct(array $options)
    {
        Assert::boolean($options['recursive'], 'Recursive option must be a boolean value');
        $this->recursive = $options['recursive'];
    }

    public function recursive(): bool
    {
        return $this->recursive;
    }
}
