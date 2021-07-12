<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Parser\CodeFinder;
use PhUml\Parser\SourceCodeFinder;

final class DigraphBuilder
{
    private DigraphConfiguration $configuration;

    public function __construct(DigraphConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function codeFinder(): CodeFinder
    {
        return $this->configuration->searchRecursively()
            ? SourceCodeFinder::recursive()
            : SourceCodeFinder::nonRecursive();
    }
}
