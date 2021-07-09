<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Parser\CodeFinder;

final class StringCodeFinder implements CodeFinder
{
    /** @var string[]  */
    private array $files;

    public function __construct()
    {
        $this->files = [];
    }

    public function add(string $definition): void
    {
        $this->files[] = $definition;
    }

    public function files(): array
    {
        return $this->files;
    }
}
